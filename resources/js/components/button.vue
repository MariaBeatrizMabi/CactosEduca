<script setup>
import { defineProps } from 'vue';
import { useRouter } from 'vue-router';
const props = defineProps({
    TextValue: {
        type: String,
        required: true
    }
})

const router = useRouter();

const handleClick = (cityName) => {
    // Concatene o valor selecionado à sua URL e redirecione
    router.push(`/SchoolDetails/${cityName}`);
};
</script>

<template>
    <a class="buttonComponent" @click="handleClick(props.TextValue)">
        {{ props.TextValue }}
    </a>
</template>

<style>
    .buttonComponent {
        width: 100%;
        background-color: var(--primary-color);
        color: var(--white-color);
        text-align: center;
        cursor: pointer;
        padding: 0.8rem 0;
        border-radius: 5rem;
        font-weight: 600;
    }

    .buttonComponent:hover {
        transition: 0.3s;
        background-color: var(--secondary-color);
        
    }
</style>