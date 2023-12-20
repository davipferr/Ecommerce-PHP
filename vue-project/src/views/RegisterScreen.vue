<script setup lang="ts">
import { ref } from 'vue';
import { createUser } from '@api/auth/firebase/credentialAuth.ts';

import api from '@u/api.ts';

import { useRouter } from 'vue-router';
const router = useRouter();

const name = ref('');
const email = ref('');
const password = ref('');
const confirmPassword = ref('');

const isLoading = ref(false);

async function register(
  clientName: string,
  clientEmail: string,
  clientPassword: string
  ) {

  isLoading.value = true;

  const createUserFireBase = await createUser(clientEmail, clientPassword)
  console.log('user', createUserFireBase);

  if(!createUserFireBase?.accessToken) {
    console.log('Não foi possível cadastrar o usuário, tente novamente mais tarde');
  }
  
  //cadastrar no banco de dados
  try {
    const responseRegisterClient = await api(
      {
        url: 'client/register',
        method: 'post',
        data: {
          clientName,
          clientEmail,
          clientPassword,
        },
      }
    );

  console.log('responseRegisterCLient', responseRegisterClient);
  } catch (error) {
    console.log('error', error);
  }

  isLoading.value = false;

  localStorage.setItem('user', JSON.stringify(createUserFireBase));
}

const redirectToLogin = () => {
  router.push({ path: '/login' });
}
</script>

<template>
  <div class="d-flex justify-center mt-10">
    <v-card
      class="mx-auto"
      width="420"
      height="450"
      elevated
      elevation="4"
      :disabled="isLoading"
    >
      <div class="my-5 mt-5 mb-8 text-center">
        <v-card-title class="font-weight-black text-h5">
          Crie sua conta
        </v-card-title>
      </div>
      <div class="mx-10">
        <div>
          <v-text-field
            label="Nome"
            variant="outlined"
            density="compact"
            clearable
            v-model="name"
          >
          </v-text-field>
        </div>
        <div>
          <v-text-field
            label="E-mail"
            variant="outlined"
            density="compact"
            clearable
            v-model="email"
          > 
          </v-text-field>
        </div>
        <div>
          <v-text-field
            label="Senha"
            variant="outlined"
            density="compact"
            clearable
            v-model="password"
          >
          </v-text-field>
        </div>
        <div>
          <v-text-field
            label="Confirmar senha"
            variant="outlined"
            density="compact"
            clearable
            v-model="confirmPassword"
          >
          </v-text-field>
        </div>
      </div>
      <div class="d-flex justify-center">
        <v-btn
          class="ma-5 rounded-pill w-75"
          variant="tonal"
          @click="register(name, email, password)"
        >
          Register
        </v-btn>
      </div>
      <div class="ma-5 text-center">
        <code>
          Já possui uma conta? 
          <span
            style="color: blue; cursor: pointer;"
            class="text-decoration-underline"
            @click="redirectToLogin()"
          >
            Login
          </span>
        </code>
      </div>
    </v-card>
  </div>
</template>