<script setup>
import { ref, onMounted } from 'vue'

const loading = ref('Carregando')

const props = defineProps({
    isLoading: {
      type: Boolean,
      required: true
    }
})

const animateLoading = () => {
  setInterval(() => {
    loading.value += '.'
    if (loading.value.length > 13) {
      loading.value = 'Carregando'
    }
  }, 500)
}

onMounted(() => {
  animateLoading()
})
</script>

<template>
    <div v-if="isLoading" class="loading-content">
        <img src="/public/assets/loadingAnimate.gif" alt="Loading">
        <h1>{{ loading }}</h1>
    </div>
</template>

<style>
    .loading-content {
        background-color: rgb(255, 255, 255);

        width: 100vw;
        height: 100vh;
        
        top: 0;
        left: 0;

        position: absolute;
        z-index: 9999;

        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        & h1 {
            font-weight: 500;
            color: #085;
        }
    }
</style>