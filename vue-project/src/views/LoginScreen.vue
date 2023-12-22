<script setup lang="ts">
import { ref } from 'vue';
import { singInUser } from '@api/auth/firebase/credentialAuth';
import { getClientByFireBase, addRefereshTokenExpiration } from '@br/client.ts';

import { useRouter } from 'vue-router';
const router = useRouter();

const email = ref('2525@gmail.com');
const password = ref('123456');
const isLoading = ref(false);
const rememberMe = ref(false);

const getClient = async () => {
  const singInClientFireBase = await singInUser(email.value, password.value);

  console.log('singInClientFireBase', singInClientFireBase);

  if(!singInClientFireBase || !singInClientFireBase.email || !singInClientFireBase.stsTokenManager?.accessToken) {
    return console.log('Usário não existe');
  }

  const response = await getClientByFireBase({
    email: singInClientFireBase?.email,
    access_token: singInClientFireBase?.stsTokenManager?.accessToken.subString(0,30),
  });

  console.log('getClientByFireBase', response);

  return response;
}

async function loginUser () {
  isLoading.value = true;

  //verifiar se os dados estão no LocalStorage

  localStorage.clear(); // **************************************
  const localStorageUser = JSON.parse(localStorage.getItem('user') || '{}');
  console.log('Storage', localStorageUser);

  // verificar se o token expirou

  if (!localStorageUser || !localStorageUser.email || !localStorageUser.stsTokenManager.accessToken) {
    
    const getClientResponse = await getClient();
    console.log('client', getClientResponse);
    // verificar se o token expirou

    if (rememberMe.value && !getClientResponse.client_tokens.refresh_token_expiration_time) {
      console.log('create token sem store');

      const response = await addRefereshTokenExpiration({
        clientId: getClientResponse.client.id,
        expiarationTime: Date.now() + 1000 * 60,
      });

      // show toast error

      console.log('response', response);

      if (response && response.data.refresh_token_expiration_time) {
        getClientResponse.client_tokens.refresh_token_expiration_time = response.data.refresh_token_expiration_time
      }
    }

    if (getClientResponse?.client_tokens?.access_token_expiration_time < Date.now()) {
      return console.log('Token inválido');
    }

    localStorage.setItem('user', JSON.stringify(getClientResponse));
  } else {

    if (rememberMe.value && !localStorageUser.client_tokens.refresh_token_expiration_time) {
      console.log('create token com store');

      const response = await addRefereshTokenExpiration({
        clientId: localStorageUser.client.id,
        expiarationTime: Date.now() + 1000 * 60,
      });
      
      console.log('response', response);

      if (response && response.data.refresh_token_expiration_time) {
        console.log('Antes', localStorage.client_tokens.refresh_token_expiration_time);

        localStorage.client_tokens.refresh_token_expiration_time = response.data.refresh_token_expiration_time

        localStorage.setItem('user', JSON.stringify('user', localStorage.client_tokens));

        console.log('Depois', localStorage.client_tokens.refresh_token_expiration_time);
      }
    }

    if (localStorageUser?.client_tokens?.access_token_expiration_time < Date.now()) {
      return console.log('Token inválido');
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