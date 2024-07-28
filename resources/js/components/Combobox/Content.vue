<script setup>
import { ComboboxContent } from 'radix-vue';
</script>

<template>
    <ComboboxContent class="ComboboxContent" >
        <slot></slot>
    </ComboboxContent>
</template>

<style scoped>
.ComboboxContent {
    position: absolute;
    overflow-y: scroll;
    width: 100%;
    background-color: white;
    border-radius: 6px;
    margin-top: 8px;
    max-height: 150px;
    border: 2px solid var(--primary-color);
    box-shadow: 0px 10px 38px -10px rgba(22, 23, 24, 0.35), 0px 10px 20px -15px rgba(22, 23, 24, 0.2);
}
</style>
