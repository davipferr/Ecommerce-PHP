<script setup lang="ts">
</script>

<template>
  <div class="d-flex all-center-column fill-all-heigth"
  >
  <div>
    <h1 class="font-size">404</h1>
  </div>
  <div>
    <v-btn
      variant="tonal"
      color="primary"
      size="x-large"
      rounded="xl"
      elevation="4"
    >
    <span>
      Ir para Home
    </span>
    </v-btn>
  </div>
  </div>
</template>

<style scoped>

.font-size {
  font-size: 10rem;
}

@media (max-width: 768px) {
  .font-size  {
    font-size: 5rem;
  }
}

@media (max-width: 425px) {
  .font-size  {
    font-size: 3rem;
  }
}
</style>