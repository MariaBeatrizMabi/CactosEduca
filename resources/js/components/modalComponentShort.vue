<script setup>
    import { ref } from 'vue'

    const Titlevalue = ref()

    const props = defineProps({
        Titlevalue: {
            type: String,
            required: true
        }
    })
</script>

<template>
    <div class="modal-background-deleted">
        <div class="modal-content-deleted">
            <div class="modal-title">
                <h1>{{ props.Titlevalue }}</h1>
                <hr>
            </div>
            <div class="modal-body">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<style >
    .modal-background-deleted {
        left: 0 !important;
        top: 0 !important;
        margin: 0 auto !important;
        position: fixed !important;
        height: 100vh !important;
        width: 100vw !important;
        background-color: rgba(0, 0, 0, 0.219);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        
        & .modal-content-deleted{
            display: flex;
            flex-direction: column;
            background-color: white;
            width: 70%;
            border-radius: 5rem;

            & .modal-title {
                width: 100%;
                padding: 0.5rem 0;
                text-align: center;
                & h1 {
                    color: var(--black-color);
                    margin: 1rem 0;
                }
                & hr {
                    background-color: var(--black-color);
                    height: 2px;
                    border: 0;
                }
            }

            & .modal-body{
                display: flex;
                flex-direction: column;
                align-items: center;
                margin: 0 !important;
                background-color: white;
                border-radius: 0 0 5rem 5rem;
                width: 100%;
        }
    }
}
</style>