<script setup>
defineProps({
    person: String
})
</script>

<template>
    <div>
        <h1 class="text-blue-900">
            My name is {{  person }}
        </h1>
    </div>
</template>