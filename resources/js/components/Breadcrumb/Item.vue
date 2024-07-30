<script setup></script>

<template>
    <li>
        <slot></slot>
    </li>
</template>

<style scoped>
li {
    display: flex;
    color: black;
    align-items: center;
    justify-self: center;
    gap: 8px;
    font-size: 0.9rem;
    line-height: 0;
    text-wrap: nowrap;
}

li + li::before {
    content: "/";
}
</style>
