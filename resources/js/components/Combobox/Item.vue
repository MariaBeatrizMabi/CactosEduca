<script setup>
import { ComboboxItem } from 'radix-vue';

const props = defineProps({
    value: {
        required: false,
        type: String
    }
});
</script>

<template>
    <ComboboxItem class="ComboboxItem" :value="props.value">
        <slot></slot>
    </ComboboxItem>
</template>

<style scoped>
.ComboboxItem {
    font-size: 13px;
    line-height: 1;
    color: black;
    margin: 4px;
    border-radius: 3px;
    display: flex;
    align-items: center;
    height: 30px;
    flex: 1;
    padding: 0 35px 0 25px;
    position: relative;
    user-Combobox: none;
    cursor: pointer;

    & > div {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
}

.ComboboxItem[data-disabled] {
    color: black;
    pointer-events: none;
}

.ComboboxItem[data-highlighted] {
    outline: none;
    background-color: var(--secondary-color);
    color: white;
}
</style>
