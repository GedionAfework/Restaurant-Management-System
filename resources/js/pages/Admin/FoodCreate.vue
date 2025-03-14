<template>
    <AdminLayout>
      <h1 class="text-2xl font-bold">Add New Food</h1>
      <form @submit.prevent="submitForm">
        <div>
          <label for="name">Food Name</label>
          <input v-model="form.name" id="name" type="text" required />
        </div>
        <div>
          <label for="price">Price</label>
          <input v-model="form.price" id="price" type="number" required />
        </div>
        <button type="submit">Add Food</button>
      </form>
    </AdminLayout>
  </template>
  
  <script>
  export default {
    data() {
      return {
        form: {
          name: '',
          price: '',
        },
      };
    },
    methods: {
      submitForm() {
        this.$inertia.post('/admin/food', this.form);
      },
    },
  };
  </script>
  