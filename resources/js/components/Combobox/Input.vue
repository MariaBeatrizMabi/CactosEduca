<script setup>
import { ComboboxAnchor, ComboboxInput, ComboboxTrigger } from 'radix-vue';
</script>

<template>
    <ComboboxAnchor class="ComboboxAnchor">
        <ComboboxInput
            class="ComboboxInput"
            placeholder="Selecione uma opção"
        />
        <ComboboxTrigger>
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 15 15"><path fill="currentColor" fill-rule="evenodd" d="M3.135 6.158a.5.5 0 0 1 .707-.023L7.5 9.565l3.658-3.43a.5.5 0 0 1 .684.73l-4 3.75a.5.5 0 0 1-.684 0l-4-3.75a.5.5 0 0 1-.023-.707" clip-rule="evenodd"/></svg>
        </ComboboxTrigger>
    </ComboboxAnchor>
</template>

<style scoped>
button, input {
    all: unset;
}

.ComboboxAnchor {
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 1rem;
    border-radius: 3rem;
    background-color: var(--grap-color);
    outline: 0;
    font-size: 13pt;
    color: black;
    border: 2px solid var(--primary-color);
}

.ComboboxInput {
    height: 100%;
    background-color: transparent;
    color: black;
    width: 100%;
}

.ComboboxInput[data-placeholder] {
    color: black;
}
</style>
