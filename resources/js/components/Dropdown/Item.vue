<script setup>
import { DropdownMenuItem } from 'radix-vue';
</script>

<template>
    <DropdownMenuItem class="dropdown-menu-item">
        <slot></slot>
    </DropdownMenuItem>
</template>

<style>
.dropdown-menu-item {
    font-size: 13px;
    line-height: 1;
    cursor: pointer;
    padding: 1rem;
    border-radius: 3px;
    display: flex;
    align-items: center;
    height: 25px;
    position: relative;
    padding-left: 25px;
    user-select: none;
    outline: none;
    gap: 8px;
}

.dropdown-menu-item[data-disabled] {
  color: var(--mauve-8);
  pointer-events: none;
}

.dropdown-menu-item[data-highlighted], .dropdown-menu-item:hover {
  background-color: rgba(0, 0, 0, .1);
}
</style>
