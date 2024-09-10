<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    TextValue: {
        type: String,
        required: true
    }
});
</script>

<template>
    <a class="buttonComponent">
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
