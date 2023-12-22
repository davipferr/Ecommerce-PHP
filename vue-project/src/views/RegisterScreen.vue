<script setup lang="ts">
import { ref } from 'vue';
import { createUser } from '@api/auth/firebase/credentialAuth.ts';

import api from '@u/api.ts';
import { formatDate } from '@u/formatDate.ts';

import { useRouter } from 'vue-router';
const router = useRouter();

const name = ref('');
const email = ref('');
const password = ref('');
const confirmPassword = ref('');
const showPassword = ref(false);
const showConfirmPassword = ref(false);

const isLoading = ref(false);

async function register(
  clientName: string,
  clientEmail: string,
  clientPassword: string
  ) {

  isLoading.value = true;

  const createUserFireBase = await createUser(clientEmail, clientPassword)
  console.log('user', createUserFireBase);

  if(!createUserFireBase  || !createUserFireBase?.email || !createUserFireBase?.stsTokenManager?.accessToken ) {
    console.log('Não foi possível cadastrar o usuário, tente novamente mais tarde');
  }
  
  //cadastrar no banco de dados
  try {
    const responseRegisterClient = await api(
      {
        url: 'client/register',
        method: 'post',
        data: {
          client_name: clientName,
          client_email : clientEmail,
          client_password: clientPassword,
          access_token: createUserFireBase?.stsTokenManager?.accessToken.substring(0, 30),
          access_token_expiration_time: formatDate(Date.now() + 1000 * 10),
          refresh_token: createUserFireBase?.stsTokenManager?.refreshToken.substring(0, 30),
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
  <div class="fill-all-heigth d-flex justify-center align-center">
    <v-card
      width="420"
      height="500"
      elevation="4"
    >
      <div class="d-flex all-center my-3">
        <v-card-title class="font-weight-black text-h5">
          Crie sua conta
        </v-card-title>
      </div>
      <div class="d-flex justify-center flex-column mx-10">
        <v-form>
          <div>
            <v-text-field
              label="Nome"
              variant="outlined"
              density="compact"
              v-model="name"
              type="text"
              :disabled="isLoading"
            >
            </v-text-field>
          </div>
          <div>
            <v-text-field
              label="E-mail"
              variant="outlined"
              density="compact"
              v-model="email"
              type="email"
              :disabled="isLoading"
              autocomplete="username"
            > 
            </v-text-field>
          </div>
          <div>
            <v-text-field
              label="Senha"
              variant="outlined"
              density="compact"
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              :disabled="isLoading"
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
          </div>
          <div>
            <v-text-field
              label="Confirmar senha"
              variant="outlined"
              density="compact"
              v-model="confirmPassword"
              :type="showConfirmPassword ? 'text' : 'password'"
              :disabled="isLoading"
              autocomplete="new-password"
            >
            <template v-slot:append-inner>
              <div>
                <font-awesome-icon
                  :icon="['fas', showConfirmPassword ? 'eye' : 'eye-slash' ]"
                  style="color: #000000; cursor: pointer;"
                  @click="showConfirmPassword = !showConfirmPassword"
                />
              </div>
            </template>
            </v-text-field>
          </div>
        </v-form>
      </div>
      <div class="d-flex justify-center">
        <v-btn
          class="ma-3 w-75"
          rounded="xl"
          color="#000000"
          @click="register(name, email, password)"
          :loading="isLoading"
        >
          <span class="font-weight-bold"> Register </span>
        </v-btn>
      </div>
      <div class="ma-5 text-center">
        <code>
          Já possui uma conta? 
          <span
            v-if="!isLoading"
            style="color: blue; cursor: pointer;"
            class="text-decoration-underline"
            @click="redirectToLogin()"
          >
            Fazer Login
          </span>
          <span
            v-if="isLoading"
            style="color: grey;"
            @click="redirectToLogin()"
          >
            Fazer Login
          </span>
        </code>
      </div>
    </v-card>
  </div>
</template>