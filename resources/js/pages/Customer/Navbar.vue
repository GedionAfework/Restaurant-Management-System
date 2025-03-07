<template>
  <nav class="bg-black shadow-md py-4 px-6 flex justify-between items-center relative">
    <!-- Hamburger Menu for Tablet and Smaller Screens -->
    <div class="lg:hidden">
      <button @click="toggleMenu" class="text-white text-2xl cursor-pointer">
        <font-awesome-icon :icon="['fas', 'bars']" />
      </button>
    </div>

    <!-- Navigation Links -->
    <div :class="[isMenuOpen ? 'flex' : 'hidden', 'lg:flex lg:flex-row lg:space-x-6 absolute lg:static top-16 left-0 w-full lg:w-auto bg-black lg:bg-transparent p-4 lg:p-0']">
      <router-link to="/" class="border-b-2 border-transparent px-4 cursor-pointer">
        <img src="../../../../assets/images/burger-logo.png" alt="Burger" class="w-12 object-cover transform">
      </router-link>

      <Link :href="reservationRoute" class="text-white font-extrabold border-b-2 border-transparent hover:border-white px-4 py-2 cursor-pointer">Reservation</Link>
      <Link :href="locationRoute" class="text-white font-extrabold border-b-2 border-transparent hover:border-white px-4 py-2 cursor-pointer">Locations</Link>
      <Link :href="giftCardsRoute" class="text-white font-extrabold border-b-2 border-transparent hover:border-white px-4 py-2 cursor-pointer">Gift Cards</Link>
      <Link :href="membershipRoute" class="text-white font-extrabold border-b-2 border-transparent hover:border-white px-4 py-2 cursor-pointer">Membership</Link>
      <Link :href="cateringRoute" class="text-white font-extrabold border-b-2 border-transparent hover:border-white px-4 py-2 cursor-pointer">Catering</Link>

      <!-- Search Bar & User Icon inside Hamburger Menu -->
      <div class="flex flex-col lg:hidden mt-4 space-y-4">
        <div class="flex items-center border px-4 py-2 rounded-md focus-within:ring-2 focus-within:ring-white">
          <font-awesome-icon :icon="['fas', 'search']" class="text-white mr-2" />
          <input type="text" v-model="searchQuery" placeholder="Search..." class="focus:outline-none w-full bg-black text-white placeholder-white">
        </div>
        <div class="space-x-2">
          <button class="bg-black text-white border-2 border-white px-4 py-2 rounded cursor-pointer">Sign Up</button>
          <button class="bg-white text-black px-4 py-2 rounded border cursor-pointer">Login</button>
        </div>
      </div>
    </div>

    <!-- Search Bar & User Icon (Visible on Larger Screens) -->
    <div class="hidden lg:flex items-center space-x-4">
      <div class="flex items-center border px-4 py-2 rounded-md focus-within:ring-2 focus-within:ring-white">
        <font-awesome-icon :icon="['fas', 'search']" class="text-white mr-2" />
        <input type="text" v-model="searchQuery" placeholder="Search..." class="focus:outline-none w-full bg-black text-white placeholder-white">
      </div>
      <div class="space-x-2">
        <button class="bg-black text-white border-2 border-white px-4 py-2 rounded cursor-pointer">Sign Up</button>
        <button class="bg-white text-black px-4 py-2 rounded border cursor-pointer">Login</button>
      </div>
    </div>
  </nav>
</template>

<!-- <script setup>
import { library } from "@fortawesome/fontawesome-svg-core";
import { faBars, faSearch, faUser } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";

  reservationRoute = route("reservation"),
  locationRoute = route("location"),
  giftCardsRoute = route("gift-cards"),
  membershipRoute = route("membership"),
  cateringRoute = route("catering"),

library.add(faBars, faSearch, faUser);

// export default {
//   components: {
//     FontAwesomeIcon,
//   },
//   data() {
//     return {
//       isMenuOpen: false,
//       searchQuery: "",
      
//     };
//   },
//   methods: {
//     toggleMenu() {
//       this.isMenuOpen = !this.isMenuOpen;
//       console.log("Menu toggled:", this.isMenuOpen);
//     },
//   },
// };
</script> -->

<script setup>
import { library } from "@fortawesome/fontawesome-svg-core";
import { faBars, faSearch, faUser } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref } from "vue";

library.add(faBars, faSearch, faUser);

const isMenuOpen = ref(false);
const searchQuery = ref("");

// Define the route variables correctly
const reservationRoute = route("reservation");
const locationRoute = route("location");
const giftCardsRoute = route("gift-cards");
const membershipRoute = route("membership");
const cateringRoute = route("catering");

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
  console.log("Menu toggled:", isMenuOpen.value);
};
</script>
