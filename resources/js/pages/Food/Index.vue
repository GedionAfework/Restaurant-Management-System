<script setup>
import { ref, onMounted } from "vue";
import { router, usePage } from "@inertiajs/vue3";

const foods = ref([]);

onMounted(() => {
    foods.value = usePage().props.foods;
});
</script>

<template>
    <div>
        <h1>Food Items</h1>
        <ul>
            <li v-for="food in foods" :key="food.id">
                {{ food.name }} - ${{ food.price }}
            </li>
        </ul>
    </div>
</template>
