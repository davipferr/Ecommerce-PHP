<script setup lang="ts">
import NavBarComponent from '@c/NavBar.vue';
import api from '@u/api';
import { useRoute, useRouter } from 'vue-router';
import { onBeforeMount } from 'vue';
import { ref } from 'vue';
import { ResponseGetStoreById } from '@t/store.ts';


const route = useRoute();
const router = useRouter();

const responseGetStoreById = ref<ResponseGetStoreById>();

async function verifyStore() {
  //usuário está autenticado?
  //se não estiver mandar pro login

  const userId = route.params.userId;

  //ser-Id -> ver se a store existe no banco 

  responseGetStoreById.value = await api({
    url: `store/get-store/${userId}`,
  });

  console.log("response ->", responseGetStoreById?.value?.data);

  // se não existir -> create/store ->

  if (!responseGetStoreById?.value?.data?.store) {
    console.log('sem loja cadastrada');
    router.push({ path: '/create-store' });
  }

  // se existir -> exibir os dados da store
}

onBeforeMount(async () => {
  console.log("mounted ->");
  await verifyStore();
});

console.log("route ->", route.params);

</script>

<template>
  <div>
    <div>
      <NavBarComponent/>
    </div>
    <div>
      <h1>name: {{responseGetStoreById?.data?.store?.name}}</h1>
    </div>
  </div>
</template>