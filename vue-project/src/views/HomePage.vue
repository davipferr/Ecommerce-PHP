<script setup lang="ts">
import { useRouter } from 'vue-router';

const router = useRouter();

function verifyUser() {
	//colocar uma variavel global se o usuário tem store ou não
	const userId = 1;

	router.push({ path: `/dashboard/${userId}` })	
}
</script>

<template>

 <div>
	Home
	<v-btn
		variant="tonal"
		@click="verifyUser"
	>
	Entrar no Store Dashboard
	</v-btn>
 </div>
</template>