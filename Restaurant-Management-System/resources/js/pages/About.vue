<script setup>
defineProps({
    user: String
})
</script>


<template>
    <div>
        <h1 class="text-red-500">
        About {{ user }}
    </h1>
    </div>
</template>