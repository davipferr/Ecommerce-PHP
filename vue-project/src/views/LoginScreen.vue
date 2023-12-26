<script setup lang="ts">
import { ref } from 'vue';
import { singInUser } from '@api/auth/firebase/credentialAuth';
import { getClientByFireBase } from '@br/client.ts';
import { refreshAccessToken, createAccessToken, createRefreshToken } from '@br/auth.ts';
import { formatDate } from '@u/formatDate';
import { useRouter } from 'vue-router';
const router = useRouter();

const email = ref('2525@gmail.com');
const password = ref('123456');
const isLoading = ref(false);
const rememberMe = ref(true);

const getClient = async () => {
  const singInClientFireBase = await singInUser(email.value, password.value);

  if(!singInClientFireBase || !singInClientFireBase.email || !singInClientFireBase.stsTokenManager?.accessToken) {
    return console.log('Usário não existe');
  }

  const getClientResponse = await getClientByFireBase({
    email: singInClientFireBase?.email,
    access_token: singInClientFireBase?.stsTokenManager?.accessToken.substring(0,30),
  });

  return {
    getClientResponse: getClientResponse,
    singInClientFireBase: singInClientFireBase,
  };
}

const getUserInStorage = async () => {
  const localStorageUser = JSON.parse(localStorage.getItem('user') || '{}');

  return localStorageUser
}

const loginUser = async () => {
  isLoading.value = true;
  
  let localStorageUser = await getUserInStorage();

  if (!localStorageUser || !localStorageUser?.client?.email || !localStorageUser?.clientTokens?.access_token) {
    
    let {getClientResponse, singInClientFireBase} = await getClient();

    if (getClientResponse?.data?.clientTokens?.access_token_expiration_time < formatDate(Date.now())) {

      const response = await refreshAccessToken(
        getClientResponse.data.client.id,
      );

      if (response?.response?.status === 500) {
        isLoading.value = false;

        console.log('Não foi possível fazer login. Tente novamente mais tarde!');

        return
      } else if (response.data.newAccessTokenExpirationTime) {
        getClientResponse.data.clientTokens.access_token_expiration_time = response.data.newAccessTokenExpirationTime;
      } else {

        getClientResponse.data.clientTokens = {};

        getClientResponse.data.clientTokens.access_token = response.data.newAccessToken;
        getClientResponse.data.clientTokens.access_token_expiration_time = response.data.newExpirationTime;
      }

    } else if(!getClientResponse?.data?.clientTokens) {

      const newAccessToken = await createAccessToken({
        id: getClientResponse.data.client.id,
        token: singInClientFireBase?.stsTokenManager?.accessToken.substring(0,30),
      });

       if (newAccessToken?.response?.status === 500) {
        isLoading.value = false;

        console.log('Não foi possível fazer login. Tente novamente mais tarde!');

        return
       } else {

        getClientResponse.data.clientTokens = {};
 
        getClientResponse.data.clientTokens.access_token = newAccessToken.data.newAccessToken;
        getClientResponse.data.clientTokens.access_token_expiration_time = newAccessToken.data.newExpirationTime;
       }
    }
 
    if (rememberMe.value && !getClientResponse.data.clientTokens.refresh_token) {

      const newRefreshToken = await createRefreshToken({
        id: getClientResponse.data.client.id,
        token: singInClientFireBase.stsTokenManager.refreshToken.substring(0, 30),
      });

      if (newRefreshToken?.response?.status === 500) {
        isLoading.value = false;

        console.log('Não foi possível fazer login. Tente novamente mais tarde!');

        return
      } else {
        getClientResponse.data.clientTokens.refresh_token = newRefreshToken.data.newRefreshToken;
        getClientResponse.data.clientTokens.refresh_token_expiration_time = newRefreshToken.data.newExpirationTime;
      }
    }

    const {client, clientTokens} = getClientResponse.data;

    localStorage.setItem('user', JSON.stringify({client, clientTokens}));
  } else {
  
    if (localStorageUser?.clientTokens?.access_token_expiration_time < formatDate(Date.now())) {

      const response = await refreshAccessToken(
        localStorageUser.client.id,
      );

      if (response?.response?.status === 500) {
        isLoading.value = false;

        console.log('Não foi possível fazer login. Tente novamente mais tarde!');

        return
      } else if (response.data.newAccessTokenExpirationTime) {

        localStorageUser.clientTokens.access_token_expiration_time = response.data.newAccessTokenExpirationTime

        localStorage.setItem('user', JSON.stringify(localStorageUser));
      } else {

        localStorageUser.clientTokens = {};

        localStorageUser.clientTokens.access_token = response.data.newAccessToken;
        localStorageUser.clientTokens.access_token_expiration_time = response.data.newExpirationTime;

        const {clientTokens} = localStorageUser

        localStorage.setItem('user', JSON.stringify(clientTokens));
      }
    }

    if (rememberMe.value && !localStorageUser?.clientTokens?.refresh_token_expiration_time) {

      const newRefreshToken = await createRefreshToken({
        id: localStorageUser.client.id,
        token: "REFRESH-TOKEN-JSON",
      });

      if (newRefreshToken?.response?.status === 500) {
        isLoading.value = false;

        console.log('Não foi possível fazer login. Tente novamente mais tarde!');

        return
      } else {

        localStorageUser.clientTokens.refresh_token = newRefreshToken.data.newRefreshToken;
        localStorageUser.clientTokens.refresh_token_expiration_time = newRefreshToken.data.newExpirationTime;

        const {clientTokens} = localStorageUser

        localStorage.setItem('user', JSON.stringify(clientTokens));
      }
    }
  }

  isLoading.value = false;
}

const redirectToRegister = () => {
  router.push({ path: '/register' });
}
</script>

<template>
  <div class="d-flex all-center fill-all-heigth">
    <v-card width="400" height="500" elevation="4" class="container-1">
      <div class="d-flex justify-center my-5">
        <v-card-title class="font-weight-black text-h5">Login</v-card-title>
      </div>
      <div>
        <div class="d-flex justify-center flex-column mx-10">
          <v-text-field
            label="E-mail"
            variant="outlined"
            density="compact"
            v-model="email"
            :disabled="isLoading"
          >
          </v-text-field>
          <v-text-field
            label="senha"
            variant="outlined"
            density="compact"
            v-model="password"
            :disabled="isLoading"
          >
          </v-text-field>
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