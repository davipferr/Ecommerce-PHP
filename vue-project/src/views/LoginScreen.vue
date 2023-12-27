<script setup lang="ts">
import { ref } from 'vue';
import { singInUser } from '@api/auth/firebase/credentialAuth';
import { getClientByFireBase } from '@br/client.ts';
import
{
  validateRefreshToken,
  refreshAccessToken,
  createAccessToken,
  addRefreshToken,
  deleteAllClientTokens,
} from '@br/auth.ts';
import { formatDate } from '@u/formatDate';
import
{
  getAuthUser,
  addUserInStorage,
} from '@s/User.ts';
import { useRouter } from 'vue-router';
const router = useRouter();

const email = ref('');
const password = ref('');
const showPassword = ref(false);
const isLoading = ref(false);
const rememberMe = ref(false);

const auth = async () => {
  const response = await getAuthUser();

  if (response) {
    // mensagem usuário já autenticado
    router.push({ path: '/' });
  }

}

/* auth(); */

const getClientByEmail = async (email: string, password: string) => {
  const singInClientFireBase = await singInUser(email, password);

  if (!singInClientFireBase || !singInClientFireBase.email || !singInClientFireBase.stsTokenManager?.accessToken) {
    isLoading.value = false;

    console.log('Usário não existe');

    return 
  }

  const clientData = await getClientByFireBase(
    singInClientFireBase?.email,
  );

  return clientData

}

const isRefreshTokenValid = async (expirationTime: any) => {
  const response = await validateRefreshToken(expirationTime);

  return response.data.refreshTokenIsValid
}

const newAccessTokenExpiration = async (clientResponse: any) => {
  // validate antes
  const response = await refreshAccessToken(
    clientResponse.data.client.id,
  );

  if (response?.response?.status === 500) {
    isLoading.value = false;

    console.log('Não foi possível fazer login. Tente novamente mais tarde!');

    return
  } else if (response.data.newAccessTokenExpirationTime) {

    return clientResponse.data.clientTokens.access_token_expiration_time = 
    response.data.newAccessTokenExpirationTime;

  } else {

    clientResponse.data.clientTokens = {};

    return clientResponse.data.clientTokens = {
      access_token: response.data.newAccessToken,
      access_token_expiration_time: response.data.newExpirationTime,
    }
  }
}

const createNewAccessToken = async (clientResponse: any) => {
  const newAccessToken = await createAccessToken({
    client_id: clientResponse.data.client.id,
    new_acces_token: 'ACCESS-TOKEN-JTW',
  });

  if (newAccessToken?.response?.status === 500) {
    isLoading.value = false;

    console.log('Não foi possível fazer login. Tente novamente mais tarde!');

    return
  } else {
    clientResponse.data.clientTokens = {};

    clientResponse.data.clientTokens.access_token = newAccessToken.data.newAccessToken;
    clientResponse.data.clientTokens.access_token_expiration_time = newAccessToken.data.newExpirationTime;
  }
}

const addNewRefreshToken = async (clientResponse: any) => {
  const newRefreshToken = await addRefreshToken({
    client_id: clientResponse.data.client.id,
    new_acces_token: 'REFRESH-TOKEN-JWT',
  });

  if (newRefreshToken?.response?.status === 500) {
    isLoading.value = false;

    console.log('Não foi possível fazer login. Tente novamente mais tarde!');

    return
  } else {
    clientResponse.data.clientTokens.refresh_token = newRefreshToken.data.newRefreshToken;
    clientResponse.data.clientTokens.refresh_token_expiration_time = newRefreshToken.data.newExpirationTime;
  }
}

const deleteClientTokens = async (clientId: number) => {
  const response = await deleteAllClientTokens(clientId);

  if (response?.response?.status === 500) {
    isLoading.value = false;

    console.log('Não foi possível fazer login. Tente novamente mais tarde!');

    return
  }
}

const loginUser = async () => {
  isLoading.value = true;

  let clientData = await getClientByEmail(email.value, password.value);

  if (clientData?.data?.clientTokens?.access_token_expiration_time <
    formatDate(Date.now())) 
  {

    const refreshTokenIsValid = await isRefreshTokenValid(
      clientData.data.clientTokens.refresh_token_expiration_time
    );

    if (!refreshTokenIsValid) {
      await deleteClientTokens(clientData.data.client.id);

      await createNewAccessToken(clientData);
    } else {
      await newAccessTokenExpiration(clientData);
    }

  } else if (!clientData?.data?.clientTokens) {

    await createNewAccessToken(clientData);
    
  }
 
  if (rememberMe.value && !clientData.data.clientTokens.refresh_token) {

    await addNewRefreshToken(clientData);
    
  }

  const {client, clientTokens} = clientData.data;

  addUserInStorage({
    clientData: client, 
    clientTokens,
  });

  isLoading.value = false;

  router.push({ path: '/' });
}

const redirectToRegister = () => {
  router.push({ path: '/register' });
}
</script>

<template>
  <div class="d-flex all-center fill-all-heigth">
    <v-card width="420" height="540" variant="outlined">
      <div class="d-flex justify-center my-5">
        <v-card-title class="font-weight-black text-h5">Login</v-card-title>
      </div>
      <div>
        <div class="d-flex justify-center flex-column mx-10">
          <v-form>
            <v-text-field
              label="E-mail"
              variant="outlined"
              density="compact"
              v-model="email"
              :disabled="isLoading"
              type="email"
              autocomplete="username"
            >
            </v-text-field>
            <v-text-field
              label="senha"
              variant="outlined"
              density="compact"
              v-model="password"
              :disabled="isLoading"
              hide-details
              :type="showPassword ? 'text' : 'password'"
              autocomplete="new-password"
            >
              <template v-slot:append-inner>
                <div>
                  <font-awesome-icon
                    :icon="['fas', showPassword ? 'eye' : 'eye-slash' ]"
                    style="color: #000000; cursor: pointer;"
                    @click="showPassword = !showPassword"
                  />
                </div>
              </template>
            </v-text-field>
          </v-form>
        </div>
        <div>
          <v-checkbox
            class="d-flex mx-9"
            v-model="rememberMe"
            color="primary"
            label="Lembrar de mim"
            :disabled="isLoading"
          >
          </v-checkbox>
        </div>
        <div class="d-flex justify-center my-2">
          <v-btn
            class="w-50"
            rounded="xl"
            elevation="4"
            @click="loginUser()"
            color="#000000"
            :loading="isLoading"
          >
          <span class="font-weight-bold"> Login </span>
          </v-btn>
        </div>
      </div>
      <div class="px-16 text-center">
        <span
          style="
            background-color: white;
            z-index: 2;
          "
          class="py-2 px-5 position-relative d-inline-block"
        >
          ou entre com
        </span>
        <hr
          style="
            top: -19px;
            z-index: 1;
          "
          class="position-relative m-0 p-0"
        >
      </div>
      <div class="ma-5">
        <div
          class="d-flex justify-center flex-wrap gap-1"
        >
          <v-btn
            class="w-25"
            :disabled="isLoading"
          >
            <font-awesome-icon :icon="['fab', 'google']" />
          </v-btn>
          <v-btn
            class="w-25"
            color="light-blue-darken-4"
            :disabled="isLoading"
          >
            <font-awesome-icon :icon="['fab', 'facebook']" />
          </v-btn>
          <v-btn
            class="w-25"
            color="blue"
            :disabled="isLoading"
          >
            <font-awesome-icon :icon="['fab', 'twitter']" />
          </v-btn>
          <v-btn
            class="w-25"
            color="black"
            :disabled="isLoading"
          >
            <font-awesome-icon :icon="['fab', 'github']" />
          </v-btn>
        </div>
      </div>
      <div class="d-flex all-center">
        <code>
          Não possui uma conta? 
          <span 
            style="color:blue; cursor: pointer;"
            class="text-decoration-underline"
            @click="redirectToRegister()"
            v-if="!isLoading"
          >
            Criar conta 
          </span>
          <span 
            v-if="isLoading"
            style="color:grey;"
            class="text-decoration-underline"
          >
            Criar conta 
          </span> 
        </code>
      </div>
    </v-card>
  </div>
</template>

<style scoped>
.class-1 {
 display: flex;
 justify-content: center;
}
</style>