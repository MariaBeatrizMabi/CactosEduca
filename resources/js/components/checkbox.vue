<script setup>
const props = defineProps({
    modelValue: {
        type: [Number, Boolean],
        required: false,
        default: null,
    },
    isChecked: {
        type: Boolean,
    },
    label: {
        type: String,
        required: true,
    },
});

const randomId = Math.random();

const emit = defineEmits(["update:modelValue", "change"]);
const checked = async function (event) {
    emit("update:modelValue", event.target.checked);
    emit("change", event.target.checked);
};
</script>

<template>
    <div class="checkbox-container">
        <input
            :id="randomId"
            :checked="isChecked"
            :value="modelValue"
            class="custom-checkbox"
            type="checkbox"
            @change="checked"
        />
        <label :for="randomId" class="checkbox-label">{{ props.label }}</label>
    </div>
</template>

<style scoped>
.checkbox-list-container {
    max-height: 200px;
    overflow-y: auto;
    padding-right: 5px;
}

.checkbox-list-container::-webkit-scrollbar-track {
    background-color: var(--grap-color);
}

.checkbox-container {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    border: 1px solid var(--black-frap-color);
    border-radius: 15px;
    background-color: var(--grap-color);
    transition: background-color 0.3s ease, transform 0.2s ease;
    margin-bottom: 10px;
}

.checkbox-container:hover {
    background-color: var(--secondary-color);
    transform: scale(1.02);
}

.custom-checkbox {
    width: 20px;
    height: 20px;
    border: 2px solid var(--primary-color);
    border-radius: 4px;
    cursor: pointer;
    transition: box-shadow 0.3s ease, background-color 0.3s ease,
        transform 0.2s ease;
    -webkit-appearance: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: var(--grap-color);
}

.custom-checkbox:checked {
    background-color: var(--yellow-color);
    border-color: var(--yellow-color);
}

.custom-checkbox:checked::before {
    content: "✔";
    color: var(--white-color);
    font-size: 14px;
    font-weight: bold;
}

.custom-checkbox:focus {
    outline: none;
    box-shadow: 0 0 0 3px var(--secondary-color);
}

.checkbox-label {
    margin-left: 14px;
    font-size: 1rem;
    font-weight: 600;
    color: var(--black-color);
    transition: color 0.3s ease;
}

.checkbox-container:hover .checkbox-label {
    color: var(--white-color);
}
</style>