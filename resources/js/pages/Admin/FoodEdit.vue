<template>
    <AdminLayout>
      <h1 class="text-2xl font-bold">Edit Food</h1>
      <form @submit.prevent="submitForm">
        <div>
          <label for="name">Food Name</label>
          <input v-model="form.name" id="name" type="text" required />
        </div>
        <div>
          <label for="price">Price</label>
          <input v-model="form.price" id="price" type="number" required />
        </div>
        <button type="submit">Update Food</button>
      </form>
    </AdminLayout>
  </template>
  
  <script>
  export default {
    props: {
      food: Object,  // Food data passed from the controller
    },
    data() {
      return {
        form: {
          name: this.food.name,
          price: this.food.price,
        },
      };
    },
    methods: {
      submitForm() {
        this.$inertia.put(`/admin/food/${this.food.id}`, this.form);
      },
    },
  };
  </script>
  