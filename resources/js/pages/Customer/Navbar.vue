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
      <Link :href="menuRoute" class="text-white font-extrabold border-b-2 border-transparent hover:border-white px-4 py-2 cursor-pointer">Menu</Link>

      <!-- Admin Panel Link (only for admins) -->
      <Link 
        v-if="isAdmin" 
        :href="route('admin.dashboard')" 
        class="text-yellow-400 font-extrabold border-b-2 border-transparent hover:border-yellow-400 px-4 py-2 cursor-pointer"
      >
        🔐 Admin Panel
      </Link>

      <!-- User Options inside Hamburger Menu (Mobile) -->
      <div class="flex flex-col lg:hidden mt-4 space-y-4">
        <div v-if="!isLoggedIn" class="space-x-2">
          <Link :href="loginRoute" class="bg-white text-black px-4 py-2 rounded border cursor-pointer">Login</Link>
          <Link :href="registerRoute" class="bg-black text-white border-2 border-white px-4 py-2 rounded cursor-pointer">Sign Up</Link>
        </div>
        <div v-if="isLoggedIn" class="space-x-2">
          <span class="text-white">Hi, {{ userName }}</span>
          <button @click="logout" class="bg-white text-black px-4 py-2 rounded border cursor-pointer">Logout</button>
        </div>
      </div>
    </div>

    <!-- Desktop Navigation (Static) -->
    <div class="hidden lg:flex items-center space-x-4">
      <!-- Admin Panel Link (Desktop, only for admins) -->
      <Link 
        v-if="isAdmin" 
        :href="route('admin.dashboard')" 
        class="text-yellow-400 font-extrabold border-b-2 border-transparent hover:border-yellow-400 px-4 py-2 cursor-pointer"
      >
        🔐 Admin Panel
      </Link>

      <!-- Greeting and Account Icon with Dropdown (Logged In) -->
      <div v-if="isLoggedIn" class="relative flex items-center space-x-4">
        <span class="text-white">Hi, {{ userName }}</span>
        <button @click="toggleAccountMenu" class="text-white">
          <font-awesome-icon :icon="['fas', 'user']" class="text-2xl" />
        </button>
        <div v-if="isAccountMenuOpen" class="absolute bg-black text-white top-full right-0 mt-2 p-2 rounded shadow-lg">
          <button @click="logout" class="w-full text-left px-4 py-2 hover:bg-gray-800">Logout</button>
        </div>
      </div>

      <!-- Login/Signup Buttons (Not Logged In) -->
      <div v-if="!isLoggedIn" class="space-x-2">
        <Link :href="loginRoute" class="bg-white text-black px-4 py-2 rounded border cursor-pointer">Login</Link>
        <Link :href="registerRoute" class="bg-black text-white border-2 border-white px-4 py-2 rounded cursor-pointer">Sign Up</Link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { library } from "@fortawesome/fontawesome-svg-core";
import { faBars, faUser } from "@fortawesome/free-solid-svg-icons"; // Removed faSearch
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { Link, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref, onMounted, watch, computed } from "vue";
import { usePermissions } from '@/composables/usePermissions';

// Add FontAwesome icons to library (removed search icon)
library.add(faBars, faUser);

// Reactive state variables
const isMenuOpen = ref(false);
const isAccountMenuOpen = ref(false);
const isLoggedIn = ref(false);

// Route definitions
const reservationRoute = route("reservation");
const locationRoute = route("location");
const giftCardsRoute = route("gift-cards");
const membershipRoute = route("membership");
const cateringRoute = route("catering");
const homeRoute = route("home");
const loginRoute = route("login");
const registerRoute = route("register");
const menuRoute = route('menu');

// Compute user name from Inertia props
const page = usePage();
const { user, isAdmin } = usePermissions();

const userName = computed(() => {
  return user.value ? user.value.name || 'User' : ''; // Fallback to 'User' if name is missing
});

// Check login status from localStorage or Inertia props
const checkLoginStatus = () => {
  const isLoggedInStorage = localStorage.getItem("isLoggedIn");
  isLoggedIn.value = isLoggedInStorage === "true";
  console.log("Checked login status:", isLoggedIn.value);
};

// Sync with Inertia page props
watch(() => page.props.auth?.user, (authUser) => {
  isLoggedIn.value = !!authUser;
  if (authUser) localStorage.setItem("isLoggedIn", "true");
  else localStorage.removeItem("isLoggedIn");
}, { immediate: true });

// Initialize login status
onMounted(() => {
  checkLoginStatus();
});

// Toggle mobile menu
const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

// Toggle account dropdown
const toggleAccountMenu = () => {
  isAccountMenuOpen.value = !isAccountMenuOpen.value;
};

// Logout function
const logout = () => {
  console.log("Logging out...");
  localStorage.removeItem("isLoggedIn");
  localStorage.removeItem("auth_token");
  isLoggedIn.value = false;
  Inertia.post(route('logout'), {}, {
    onSuccess: () => {
      Inertia.visit(route('home'));
    },
  });
};
</script>