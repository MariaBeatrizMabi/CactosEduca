<script setup>
import { ComboboxRoot } from 'radix-vue';
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    search: {
        required: true,
        type: String
    },
    value: {
        required: false
    }
});

const emit = defineEmits(['update:search', 'update:value']);
const handleUpdateSearch = (value) => emit('update:search', value);
const handleUpdateValue = (value) => emit('update:value', value);
</script>

<template>
    <div class="Wrapper">
        <ComboboxRoot
            class="ComboboxRoot"
            :modelValue="props.value"
            :searchTerm="props.search"
            @update:searchTerm="handleUpdateSearch"
            @update:modelValue="handleUpdateValue"
        >
            <slot></slot>
        </ComboboxRoot>
    </div>
</template>

<style scoped>
.Wrapper {
    position: relative;
}

.ComboboxRoot {
    width: 100%;
    position: relative;
}
</style>
