<script setup>
const props = defineProps({
    labelTitle: {
        type: String,
        required: true,
    },
    placeholderValue: {
        type: String,
        required: true,
    },
    disabled: {
        type: Boolean,
        required: false,
    },
    icon: {
        required: true,
    },
    typeValue: {
        required: true,
    },
    value: {},
    RightAction: {
        type: Boolean,
        required: false
    },
    RightEmoji: {
        type: String,
        required: false
    },
    valueField: {
        type: String,
        required: true,
        default: 'name'
    }
})
</script>

<template>
    <div class="input-component">
        <label for="">{{ props.labelTitle }}</label>
        <div class="input-formating">
            <svg width="9" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                <path :d="props.icon"/>
            </svg>
            <hr>
            <select :disabled="props.disabled" v-model="props.value" :placeholder="props.placeholderValue" :type="props.typeValue">
                <option value="" disabled selected>Selecione sua opção</option>
                <slot />
            </select>
        </div>
    </div>
</template>

<style scoped>
.input-component {
    display: flex;
    flex-direction: column;

    & label {
        font-size: 14px;
        margin-bottom: 3px;
        font-weight: 600;
        color: var(--black-color);
    }

    & select {
        width: 100%;
        padding: 1rem;
        border-radius: 3rem;
        border: 0;
        margin-right: 1rem;
        border-left: 0;
        background-color: var(--grap-color);
        outline: 0;
        font-size: 13pt;
    }

    & .input-formating{
        display: flex;
        background-color: var(--grap-color);
        border: 2px solid var(--primary-color);
        border-radius:  2rem;

        & svg {
            background-color: var(--grap-color);
            border: 0;
            border-radius: 5rem;
            margin-left: 0.5rem;
            width: 3.5rem;
            padding: 0 1rem;
            fill: var(--primary-color);
        }

        & hr {
            margin: 1rem 0;
        }
    }
}

@media (max-width: 700px) {
    .input-component {
        & select {
            padding: 0.8rem 1rem 0.8rem 1rem;
        }
    }
}
</style>
