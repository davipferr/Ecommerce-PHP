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

.teste {
  contain-intrinsic-height: 500px;
}

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

/* 
  display: flex;  main axis (horizontal) | cross axis (vertical) 

*******************

  flex-direction: row; (horizontal, default)
  flex-direction: column; (vertical)

*******************

  justify-content -> align items on the main axis

  justify-content: flex-start; (elements at the start)
  justify-content: flex-end; (elements at the end)
  justify-content: center; (elements at the middle)
  justify-content: space-between; (space between first and last item)
  justify-content: space-around; (space-between with space to the edges)
  justify-content: space-evenly; (evenly distribute space between all the items)
  

  align-items -> align items on the cross axis
  
  align-items: flex-start; (elements at the start)
  align-items: flex-end; (elements at the end)
  align-items: center; (elements at the middle)
  align-items: baseline; (elements aligned at the baseline)
  align-items: stretch; (elements stretch to fill the container)


  align-self -> overwrite align-items, apply on a indicidual item

  align-self: flex-start;
  align-self: flex-end;
  align-self: center;
  align-self: baseline;
  align-self: stretch;

*******************

  flex-wrap

  flex-wrap: nowrap; (default)
  flex-wrap: wrap; (wrap items to the next line)
  flex-wrap: wrap-reverse; (wrap items to the previous line)

  align-content -> 

    align items on the cross axis

    only works when flex-wrap: wrap 

    and items are wrapping

  align-content: center;
  align-content: flex-start;
  align-content: flex-end;
  align-content: space-between;
  align-content: space-around;
  align-content: space-evenly;

*******************

  gap: 1rem; -> gap betwwen each item
  
*******************

  shorthand

  flex: ;


  Specific cases

  flex-grow: 1; -> 
  
  allow the item to grow

  if more than 1 item has flex-grow: 1; 
  the space will be distibute equally to all the items

 
  flex-shrink -> how fast a item shrinks

  flex-shrink: 0; -> no shrink 
  flex-shrink: 5; -> 5 times faster than the default


  flex-basis -> define 


*******************

  order

  

*/
</style>