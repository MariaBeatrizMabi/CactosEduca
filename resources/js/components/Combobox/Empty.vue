<script setup>
import { ComboboxEmpty } from 'radix-vue';
</script>

<template>
    <ComboboxEmpty class="ComboboxEmpty">
        <slot></slot>
    </ComboboxEmpty>
</template>

<style scoped>
.ComboboxEmpty {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    text-align: center;
    font-size: 0.75rem;
    line-height: 1rem;
    font-weight: 500;
    color: red;
    width: 100%;
    display: flex;
}
</style>
