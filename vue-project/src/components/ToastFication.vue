<script setup lang="ts">
import { watch } from 'vue';
import { ref } from 'vue';

const toast = () => {

}

const props = withDefaults(
  defineProps<{
    message: string
    showSnackbar: boolean
    snackbarColor: string
    snackbarWidth?: string
    snackbarHeight?: string
  }>(),
  {
    showSnackbar: false,
    snackbarColor: '',
    snackbarWidth: '400',
    snackbarHeight: '80',
  }
)

const emit = defineEmits<{
  (e: 'update:showSnackbar', value: boolean): void
}>()

const showToast = ref(props.showSnackbar);

watch(
  () => props.showSnackbar,
  (novoValor) => {
    showToast.value = novoValor
  }
)

watch(
  () => showToast.value,
  (novoValor) => {
    emit('update:showSnackbar', novoValor)
  }
)
</script>

<template>
  <div class="text-center">
    <v-snackbar
      class="break-word"
      v-model="showToast"
      elevation="24"
      rounded="sm"
      variant="flat"
      position="absolute"
      :color="snackbarColor"
      :width="snackbarWidth"
      :height="snackbarHeight"
      :text="message"
    >
    <template v-slot:actions>
      <v-btn
        color="red"
        variant="outlined"
        density="comfortable"
        rounded="xl"
        @click="showToast = false"
      >
        <font-awesome-icon :icon="['fas', 'xmark']"/>
      </v-btn>
    </template>
    </v-snackbar>
  </div>
</template>