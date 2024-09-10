<script setup>
import { DropdownMenuTrigger } from 'radix-vue';
</script>

<template>
    <DropdownMenuTrigger class="dropdown-menu-trigger">
        <slot></slot>
    </DropdownMenuTrigger>
</template>

<style scoped>
button {
    all: unset;
}

.dropdown-menu-trigger {
    display: flex;
    align-items: end;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    justify-content: center;
    border-radius: 2rem;
    border: 1px solid var(--secondary-color);
    background-color: white;
    align-items: center;
    color: var(--secondary-color);
    cursor: pointer;
    font-size: 1rem;
}

.dropdown-menu-trigger:hover {
    color: var(--primary-color);

    & svg > path {
        fill: var(--primary-color);
    }
}
</style>
