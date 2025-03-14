<template>
  <nav class="bg-black shadow-md py-4 px-6 flex justify-between items-center relative">
    <!-- Hamburger Menu for Tablet and Smaller Screens -->
    <div class="lg:hidden">
      <button @click="toggleMenu" class="text-white text-2xl cursor-pointer">
        <font-awesome-icon :icon="['fas', 'bars']" />
      </button>
    </div>

    <!-- Navigation Links -->
    <div :class="[isMenuOpen ? 'flex' : 'hidden', 'lg:flex lg:flex-row lg:space-x-6 absolute lg:static top-16 left-0 w-full lg:w-auto bg-black lg:bg-transparent p-4 lg:p-0', 'flex-col space-y-4 lg:space-y-0 lg:flex-row']">
      <Link :href="homeRoute" class="border-b-2 border-transparent px-4 cursor-pointer">
        <img src="../../../../assets/images/burger-logo.png" alt="Burger" class="w-12 object-cover transform">
      </Link>

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
        <div v-if="!isLoggedIn" class="space-x-2">
          <Link :href="loginRoute" class="bg-white text-black px-4 py-2 rounded border cursor-pointer">Login</Link>
          <Link :href="registerRoute" class="bg-black text-white border-2 border-white px-4 py-2 rounded cursor-pointer">Sign Up</Link>
        </div>
        <div v-if="isLoggedIn" class="space-x-2">
          <button @click="logout" class="bg-white text-black px-4 py-2 rounded border cursor-pointer">Logout</button>
        </div>
      </div>
    </div>

    <!-- Desktop Navigation (Static) -->
    <div class="hidden lg:flex items-center space-x-4">
      <div class="flex items-center border px-4 py-2 rounded-md focus-within:ring-2 focus-within:ring-white">
        <font-awesome-icon :icon="['fas', 'search']" class="text-white mr-2" />
        <input type="text" v-model="searchQuery" placeholder="Search..." class="focus:outline-none w-full bg-black text-white placeholder-white">
      </div>
      
      <!-- Account Icon (Shows only when logged in) -->
      <div v-if="isLoggedIn" class="relative">
        <button @click="toggleAccountMenu" class="text-white">
          <font-awesome-icon :icon="['fas', 'user']" class="text-2xl" />
        </button>
        <!-- Account Menu -->
        <div v-if="isAccountMenuOpen" class="absolute bg-black text-white top-full right-0 mt-2 p-2 rounded shadow-lg">
          <button @click="logout" class="px-4 py-2 text-black hover:bg-white">Logout</button>
        </div>
      </div>

      <!-- Login and Signup Buttons (Shows only when not logged in) -->
      <div v-if="!isLoggedIn" class="space-x-2">
        <Link :href="loginRoute" class="bg-white text-black px-4 py-2 rounded border cursor-pointer">Login</Link>
        <Link :href="registerRoute" class="bg-black text-white border-2 border-white px-4 py-2 rounded cursor-pointer">Sign Up</Link>
      </div>
    </div>
  </nav>
</template>
<script setup>
import { library } from "@fortawesome/fontawesome-svg-core";
import { faBars, faSearch, faUser } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref, onMounted, watch } from "vue";

library.add(faBars, faSearch, faUser);

const isMenuOpen = ref(false);
const isAccountMenuOpen = ref(false);
const searchQuery = ref("");
const isLoggedIn = ref(false); // Assume not logged in initially

// Define the route variables correctly
const reservationRoute = route("reservation");
const locationRoute = route("location");
const giftCardsRoute = route("gift-cards");
const membershipRoute = route("membership");
const cateringRoute = route("catering");
const homeRoute = route("home");
const loginRoute = route('login');
const registerRoute = route('register');

// This function will check if the user is logged in by verifying local storage
const checkLoginStatus = () => {
  const isLoggedInStorage = localStorage.getItem("isLoggedIn");
  if (isLoggedInStorage === "true") {
    console.log("User is logged in");
    isLoggedIn.value = true;
  } else {
    console.log("User is NOT logged in");
    isLoggedIn.value = false;
  }
};

// Run the login status check on component mount
onMounted(() => {
  checkLoginStatus();
});

// Watch for changes to isLoggedIn (for debugging)
watch(isLoggedIn, (newValue) => {
  console.log("isLoggedIn changed to:", newValue);
});

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

// Toggle account menu visibility
const toggleAccountMenu = () => {
  isAccountMenuOpen.value = !isAccountMenuOpen.value;
};

// Log out function
const logout = () => {
  console.log("Logging out...");
  localStorage.removeItem("isLoggedIn"); // Remove login state
  localStorage.removeItem("auth_token"); // Remove token to log out the user
  isLoggedIn.value = false; // Set logged out state
  window.location.href = "/"; // Optionally redirect to homepage or login page
};

// Simulate login (for testing purposes)
const simulateLogin = () => {
  console.log("Simulating login...");
  localStorage.setItem("isLoggedIn", "true");
  isLoggedIn.value = true;
};

// Simulate logout (for testing purposes)
const simulateLogout = () => {
  console.log("Simulating logout...");
  localStorage.removeItem("isLoggedIn");
  isLoggedIn.value = false;
};
</script>