<script setup>
import { DropdownMenuContent } from 'radix-vue';
</script>

<template>
    <DropdownMenuContent align="end" class="dropdown-menu-content">
        <slot></slot>
    </DropdownMenuContent>
</template>

<style>
.dropdown-menu-content {
    min-width: 220px;
    background-color: white;
    border-radius: 6px;
    padding: 5px;
    box-shadow: 0px 10px 38px -10px rgba(22, 23, 24, 0.35), 0px 10px 20px -15px rgba(22, 23, 24, 0.2);
    animation-duration: 400ms;
    animation-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
    border: 1px solid var(--secondary-color);
    will-change: transform, opacity;
    margin-top: 6px;
}

.dropdown-menu-content[data-side='top'] {
  animation-name: slideDownAndFade;
}
.dropdown-menu-content[data-side='right'] {
  animation-name: slideLeftAndFade;
}
.dropdown-menu-content[data-side='bottom'] {
  animation-name: slideUpAndFade;
}
.dropdown-menu-content[data-side='left'] {
  animation-name: slideRightAndFade;
}
</style>
