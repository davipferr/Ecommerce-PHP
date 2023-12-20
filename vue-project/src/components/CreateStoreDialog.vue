<script setup lang="ts">
import {ref} from 'vue';

import serverRequest from '../../utils/api.ts';
import ToastFicationComponent from '@/components/ToastFication.vue';

const openModal = ref(true);
const isLoading = ref(false);
const storeName = ref('');

const toast = ref({
  open: false,
  message: '',
  color: '',
});

type ButtonVariant = "flat" | "text" | "elevated" | "tonal" | "outlined" | "plain";

withDefaults(
  defineProps<{
  buttonText?: string
  cardTitle?: string
  cardSubTitle?: string
  buttonCancelColor?: string
  buttonCancelVariant?: ButtonVariant
  buttonConfirmColor?: string
  buttonConfirmVariant?: ButtonVariant
}>(),
{
  buttonText: 'Criar Store',
  cardTitle: 'Criar Store',
  cardSubTitle: 'Adicione uma nova store',
  buttonCancelColor: 'red',
  buttonCancelVariant: 'flat',
  buttonConfirmColor: 'success',
  buttonConfirmVariant: 'flat',
})

async function registerStore(storeName: string){
  isLoading.value = true

  if(!storeName) {
    toast.value.message = "Preecha o nome da Loja";
    toast.value.color = "";
    toast.value.open = true;
    return isLoading.value = false;
  }

  //get client id
  const userId = ref(1);

  if(!userId.value) {
    return isLoading.value = false;
  }

 /*  const responseRegisterStore = await serverRequest(
    'store/create',
    'post',
    {
      storeName,
      clientId: userId.value,
    }
  ) */

  isLoading.value = false;

  //redirecionar  dashboard
  

  /* console.log(`data -     -->`, responseRegisterStore); */
}

</script>

<template>
  <div>
    <div>
      <ToastFicationComponent 
        :showSnackbar="toast.open"
        @update:showSnackbar="toast.open = $event"
        :message="toast.message"
        :snackbarColor="toast.color"
      />
    </div>
    <v-dialog
      v-model="openModal"
      width="600"
      persistent
    >
      <template v-slot:activator="{ props }">
        <v-btn
          v-bind="props"
        >
          {{ buttonText }}
        </v-btn>
      </template>
      <v-card> 
        <div>
          <v-card-title>
            {{ cardTitle }}
          </v-card-title>
        </div>
        <div>
          <v-card-subtitle>
            {{ cardSubTitle }}
          </v-card-subtitle>
        </div>
        <div class="ma-10">
          <v-text-field
            class="w-75"
            label="Nome"
            placeholder="E-commerce"
            variant="outlined"
            density="compact"
            v-model="storeName"
            clearable
            :disabled="isLoading"
          >
          </v-text-field>
        </div>
        <div>
          <v-card-actions class="d-flex justify-end">
            <v-btn
              class="mr-3"
              :color="buttonCancelColor"
              :variant="buttonCancelVariant"
              :disabled="isLoading"
              @click="openModal = false"
            >
              Cancelar
            </v-btn>
            <v-btn
              :color="buttonConfirmColor"
              :variant="buttonConfirmVariant"
              :disabled="isLoading"
              @click="registerStore(storeName)"
            >
              Continuar
            </v-btn>
          </v-card-actions>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>