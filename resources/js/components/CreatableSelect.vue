<script setup>
import {
    ComboboxAnchor,
    ComboboxContent,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxRoot,
    ComboboxTrigger,
    ComboboxViewport
} from 'radix-vue';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    options: {
        required: true,
        type: Array
    },
    defaultValue: {
        required: false,
        type: String
    }
});

const emit = defineEmits(['create', 'change', 'delete']);

const activeItem = ref();
const search = ref('');

const showCreateButton = computed(() =>
    search.value.length > 0 && !(props.options
        .map(option => option.toLowerCase())
        .includes(search.value.toLowerCase()))
);

function handleUpdateModelValue(value) {
    emit(
        value === search.value ? 'create' : 'change',
        value
    );
}

onMounted(() => activeItem.value = props.defaultValue);
</script>

<template>
    <div class="Wrapper">
        <ComboboxRoot
            v-model="activeItem"
            class="ComboboxRoot"
            v-model:searchTerm="search"
            @update:modelValue="handleUpdateModelValue"
        >
            <ComboboxAnchor class="ComboboxAnchor">
                <ComboboxInput
                    class="ComboboxInput"
                    placeholder="Placeholder..."
                />
                <ComboboxTrigger>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 15 15"><path fill="currentColor" fill-rule="evenodd" d="M3.135 6.158a.5.5 0 0 1 .707-.023L7.5 9.565l3.658-3.43a.5.5 0 0 1 .684.73l-4 3.75a.5.5 0 0 1-.684 0l-4-3.75a.5.5 0 0 1-.023-.707" clip-rule="evenodd"/></svg>
                </ComboboxTrigger>
            </ComboboxAnchor>
            <ComboboxContent class="ComboboxContent">
                <ComboboxViewport class="ComboboxViewport">
                    <ComboboxEmpty class="ComboboxEmpty">
                        <ComboboxGroup v-if="showCreateButton">
                            <ComboboxItem
                                class="ComboboxItem"
                                :value="search"
                            >
                                <span>Criar "{{ search }}"</span>
                            </ComboboxItem>
                        </ComboboxGroup>
                    </ComboboxEmpty>

                    <ComboboxGroup>
                        <ComboboxItem
                            v-if="showCreateButton"
                            :key="search"
                            :value="search"
                            class="ComboboxItem"
                        >
                            <span>Criar "{{ search }}"</span>
                        </ComboboxItem>
                        <ComboboxItem
                            v-for="option in props.options"
                            :key="option"
                            class="ComboboxItem"
                            :value="option"
                        >
                            <div>
                                <span>{{ option }}</span>
                                <button @click="emit('delete', option)">
                                    <svg height="14" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.43214 0.725977L5.14286 1.3125H1.28571C0.574554 1.3125 0 1.89902 0 2.625C0 3.35098 0.574554 3.9375 1.28571 3.9375H16.7143C17.4254 3.9375 18 3.35098 18 2.625C18 1.89902 17.4254 1.3125 16.7143 1.3125H12.8571L12.5679 0.725977C12.3509 0.278906 11.9049 0 11.4188 0H6.58125C6.09509 0 5.64911 0.278906 5.43214 0.725977ZM16.7143 5.25H1.28571L2.1375 19.1543C2.20179 20.192 3.04554 21 4.06205 21H13.9379C14.9545 21 15.7982 20.192 15.8625 19.1543L16.7143 5.25Z" fill="#253138"/></svg>
                                </button>
                            </div>
                        </ComboboxItem>
                    </ComboboxGroup>
                </ComboboxViewport>
            </ComboboxContent>
        </ComboboxRoot>
    </div>
</template>

<style scoped>
.Wrapper {
    position: relative;
}

button, input {
    all: unset;
}

.ComboboxRoot {
    width: 100%;
    position: relative;
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
}

.ComboboxInput[data-placeholder] {
    color: black;
}

.ComboboxIcon {
    width: 16px;
    height: 16px;
    color: black;
}

.ComboboxContent {
    position: absolute;
    width: 100%;
    background-color: white;
    border-radius: 6px;
    margin-top: 8px;
    max-height: 150px;
    border: 2px solid var(--primary-color);
    box-shadow: 0px 10px 38px -10px rgba(22, 23, 24, 0.35), 0px 10px 20px -15px rgba(22, 23, 24, 0.2);
}

.ComboboxViewport {
    padding: 5px;
}

.ComboboxEmpty {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    text-align: center;
    font-size: 0.75rem;
    line-height: 1rem;
    font-weight: 500;
    color: red;
    width: 100%;
    display: flex;
}

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

.ComboboxLabel {
    padding: 0 25px;
    font-size: 12px;
    line-height: 25px;
    color: yellow;
}

.ComboboxSeparator {
    height: 1px;
    background-color: black;
    margin: 5px;
}

.ComboboxItemIndicator {
    position: absolute;
    left: 0;
    width: 25px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
</style>
