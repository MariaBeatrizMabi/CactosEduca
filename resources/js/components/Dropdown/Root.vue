<script setup>
import { DropdownMenuRoot } from 'radix-vue';

const open = defineModel('open')
</script>

<template>
    <DropdownMenuRoot v-model:open="open">
        <slot></slot>
    </DropdownMenuRoot>
</template>
