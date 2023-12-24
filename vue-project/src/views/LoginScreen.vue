<script setup lang="ts">
import { ref } from 'vue';
import { singInUser } from '@api/auth/firebase/credentialAuth';
import { getClientByFireBase, addRefereshToken } from '@br/client.ts';
import { refreshAccessToken, createAccessToken } from '@br/auth.ts';

import type { GetClientByFireBase  } from '@t/client/GetClientByFireBase';

import { useRouter } from 'vue-router';
import type api from '@u/api';
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
    access_token: singInClientFireBase?.stsTokenManager?.accessToken.substring(0,30),
  });

  return response;
}

async function loginUser () {
  isLoading.value = true;

  localStorage.clear(); // **************************************
  const localStorageUser = JSON.parse(localStorage.getItem('user') || '{}');
  console.log('Storage', localStorageUser);

  if (!localStorageUser || !localStorageUser.email || !localStorageUser.client_tokens.accessToken) {
    
    const getClientResponse = await getClient();
    console.log('client', getClientResponse);

    //verificar se access_token é valido
    if (getClientResponse?.client_tokens?.access_token_expiration_time < Date.now()) {

      const response = await refreshAccessToken(
        getClientResponse.client.id,
      );

      if (response.status === 500) {
        console.log('Não foi possível fazer login. Tente novamente mais tarde!');
      } else if (response.data.newAccessTokenExpirationTime) {
        getClientResponse.client_tokens.access_token_expiration_time = response.data.newAccessTokenExpirationTime
      } else {
        //tokens deletados

        const newAccessToken = await createAccessToken({
          id: getClientResponse.client.id,
          token: getClientResponse.client_tokens.access_token,
        });

       if (newAccessToken.status === 500) {
        console.log('Não foi possível fazer login. Tente novamente mais tarde!');
       } else {
          getClientResponse.client_tokens.access_token = newAccessToken.data.newAccessToken;
          getClientResponse.client_tokens.access_token_expiration_time = newAccessToken.data.newExpirationTime;
       }
      }

    }
    
    //criar refresh_token
    if (rememberMe.value && !getClientResponse.client_tokens.refresh_token_expiration_time) {

      const newRefreshToken = await addRefereshToken({
        clientId: getClientResponse.client.id,
        refreshToken:  getClientResponse.client_tokens.refresh_token.substring(0, 30),
      });

      console.log('response', newRefreshToken);

      if (newRefreshToken.status === 500) {
        console.log('Não foi possível fazer login. Tente novamente mais tarde!');
      } else {
        getClientResponse.client_tokens.refresh_token = newRefreshToken.data.newRefreshToken;
        getClientResponse.client_tokens.refresh_token_expiration_time = newRefreshToken.data.newExpirationTime;
      }
    }

    localStorage.setItem('user', JSON.stringify(getClientResponse));
  } else {

    if (localStorageUser?.client_tokens?.access_token_expiration_time < Date.now()) {

      const response = await refreshAccessToken(
        localStorageUser.client.id,
      );

      if (response.status === 500) {
        console.log('Não foi possível fazer login. Tente novamente mais tarde!');
      } else if (response.data.newAccessTokenExpirationTime) {
        localStorageUser.client_tokens.access_token_expiration_time = response.data.newAccessTokenExpirationTime

        localStorage.setItem('user', JSON.stringify(localStorageUser));
      } else {
        //tokens deletados

        const newAccessToken = await createAccessToken({
          id: localStorageUser.client.id,
          token: localStorageUser.client_tokens.access_token,
        });

        if (newAccessToken.status === 500) {
          console.log('Não foi possível fazer login. Tente novamente mais tarde!');
        } else {
          localStorageUser.client_tokens.access_token = newAccessToken.data.newAccessToken;
          localStorageUser.client_tokens.access_token_expiration_time = newAccessToken.data.newExpirationTime;

          localStorage.setItem('user', JSON.stringify(localStorageUser));
        }
      }
    }

    if (rememberMe.value && !localStorageUser.client_tokens.refresh_token_expiration_time) {

      const newRefreshToken = await addRefereshToken({
        clientId: localStorageUser.client.id,
        refreshToken:  localStorageUser.client_tokens.refresh_token.substring(0, 30),
      });

      console.log('response', newRefreshToken);

      if (newRefreshToken.status === 500) {
        console.log('Não foi possível fazer login. Tente novamente mais tarde!');
      } else {
        localStorageUser.client_tokens.refresh_token = newRefreshToken.data.newRefreshToken;
        localStorageUser.client_tokens.refresh_token_expiration_time = newRefreshToken.data.newExpirationTime;

        localStorage.setItem('user', JSON.stringify(localStorageUser));
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