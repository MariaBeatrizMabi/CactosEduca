<script setup>
const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    placeholder: {
        type: String,
        required: true,
    },
    disabled: {
        type: Boolean,
        required: false,
    },
    type: {
        type: String,
        required: true,
    },
    modelValue: {
        type: String,
        required: true
    }
});
</script>

<template>
    <div class="input-component">
        <label for="">{{ props.labelTitle }}</label>
        <input
            autocomplete="off"
            :disabled="props.disabled"
            :value="props.modelValue"
            :placeholder="props.placeholder"
            :type="props.type"
        />
    </div>
</template>
