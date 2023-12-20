<script setup lang="ts">
import { ref } from 'vue';
import { singInUser } from '@api/auth/firebase/credentialAuth';

import { useRouter } from 'vue-router';
const router = useRouter();

const email = ref('');
const password = ref('');
const isLoading = ref(false);

async function loginUser () {
  isLoading.value = true;

  //verifiar se os dados estão no LocalStorage

  const singInUserFireBase = ref(JSON.parse(localStorage.getItem('user') || '{}'));
  console.log(singInUserFireBase.value);

  if (!singInUserFireBase.value) {
    singInUserFireBase.value = await singInUser(email.value, password.value);

    localStorage.setItem('user', JSON.stringify(singInUserFireBase.value));
  }
  
  isLoading.value = false;

  if(!singInUserFireBase?.value?.stsTokenManager?.accessToken) {
    return console.log('Usário não existe');
  }

  // verificar se o accessToken é valido, se ofor in válido verificar a o refreseh token
}

const redirectToRegister = () => {
  router.push({ path: '/register' });
}
</script>

<template>
  <div class="mt-10">
    <v-card class="mx-auto" width="420" height="500" elevated elevation="4">
      <div class="my-5 mt-5 mb-8 text-center">
        <v-card-title class="font-weight-black text-h5">Login</v-card-title>
      </div>
      <div class="mx-10">
        <v-text-field
          label="E-mail" 
          variant="outlined" 
          density="compact"
          clearable
          v-model="email"
        >
        </v-text-field>
        <v-text-field
          label="senha" 
          variant="outlined"
          density="compact"
          clearable
          v-model="password"
        >
        </v-text-field>
      </div>
      <div class="d-flex justify-center">
        <v-btn
          class="rounded-pill w-50 mb-3"
          variant="tonal"
          @click="loginUser()"
        >
          Login
        </v-btn>
      </div>
      <div class="px-16 text-center">
        <span style="background-color: white; z-index: 2;" class="py-2 px-5 position-relative d-inline-block">ou entre com</span>
        <hr style="top: -17px; z-index: 1;" class="position-relative m-0 p-0">
      </div>
      <div class="ma-5">
        <div class="d-flex justify-center mb-3">
          <v-btn class="w-50" variant="outlined">Google</v-btn>
        </div>
        <!-- <div class="d-flex justify-center mb-3">
          <v-btn class="w-50" variant="outlined">Facebook</v-btn>
        </div>
        <div class="d-flex justify-center">
          <v-btn class="w-50" variant="outlined">Twitter</v-btn>
        </div> -->
      </div>
      <div class="mt-10 text-center">
        <code>
          Não possui uma conta? 
          <span 
            style="color:blue; cursor: pointer;"
            class="text-decoration-underline"
            @click="redirectToRegister()"
          >
            Criar conta 
          </span> 
        </code>
      </div>
    </v-card>
  </div>
</template>