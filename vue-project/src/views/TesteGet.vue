<script setup lang="ts">
import api from '@u/api';
import { ref } from 'vue';

const getClientsResponse = ref();
const startTime = ref();
const endTime = ref();


const get = async () => {

  startTime.value = console.time();

  getClientsResponse.value = await api({
    url: 'teste/get',
  })

  endTime.value = console.timeEnd();

  console.log('response', getClientsResponse.value);
}

get();
</script>

<template>
  <div class="container">
    <div>
      <h1 class="font">
      Get
    </h1>
    </div>
    <div class="item-2" v-for="(client, index) in getClientsResponse?.data?.clients" :key="index">
      <span>{{ client }}</span>
    </div>
    
  </div>
</template>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 2rem;
}
.item-2 {
  align-self: flex-start;
  margin-left: calc(100% - 95%);
  margin-right: calc(100% - 95%);
  font-size: 1.3rem;
}

.font {
  font-size: 5rem;
}

</style>