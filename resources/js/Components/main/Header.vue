<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue'
import Sidebar from '@/Components/main/Sidebar.vue';
import { initFlowbite } from 'flowbite'
import {
    NavigationMenu,
    NavigationMenuContent,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    NavigationMenuTrigger,
    navigationMenuTriggerStyle,
} from '@/Components/ui/navigation-menu'

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/Components/ui/dialog'
import CreateProject from './header/CreateProject.vue';
import Button from '../ui/button/Button.vue';
import LoginModal from './header/LoginModal.vue';

const components: { title: string, href: string, description: string }[] = [
    {
        title: 'Alert Dialog',
        href: '/docs/components/alert-dialog',
        description:
            'A modal dialog that interrupts the user with important content and expects a response.',
    },
    {
        title: 'Hover Card',
        href: '/docs/components/hover-card',
        description:
            'For sighted users to preview content available behind a link.',
    },
    {
        title: 'Progress',
        href: '/docs/components/progress',
        description:
            'Displays an indicator showing the completion progress of a task, typically displayed as a progress bar.',
    },
    {
        title: 'Scroll-area',
        href: '/docs/components/scroll-area',
        description: 'Visually or semantically separates content.',
    },
    {
        title: 'Tabs',
        href: '/docs/components/tabs',
        description:
            'A set of layered sections of content—known as tab panels—that are displayed one at a time.',
    },
    {
        title: 'Tooltip',
        href: '/docs/components/tooltip',
        description:
            'A popup that displays information related to an element when the element receives keyboard focus or the mouse hovers over it.',
    },
]

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

const isSidebarVisible = ref(false);

const sidebarToggle = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};

onMounted(() => {
    initFlowbite();
})
</script>

<template>
    <div class="w-full h-28 grid grid-cols-3 items-center px-10">
        <!-- <div class="items-center flex h-full">
            <button @click="sidebarToggle" class="absolute left-10 text-white font-bold z-10">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m9 5 7 7-7 7" />
                </svg>
            </button>

            <div v-if="isSidebarVisible" @click="sidebarToggle"
                class="fixed top-0 left-0 w-full h-full bg-black/40 z-[39] backdrop-blur-[1px]">
            </div>
            <Sidebar :isVisible="isSidebarVisible" @toggle="sidebarToggle" />
        </div> -->
        <div class="justify-self-start">
            <Link :href="route('index')">
            <img src="/img/logo.svg" alt="" class="transition-all hover:opacity-80 active:opacity-100">
            </Link>
        </div>
        <NavigationMenu class="justify-self-center">
            <NavigationMenuList>
                <NavigationMenuItem v-if="$page.props.auth.user">
                    <NavigationMenuLink :href="route('profile.index', { id: $page.props.auth.user.id })"
                        :class="navigationMenuTriggerStyle()">
                        Профиль
                    </NavigationMenuLink>
                </NavigationMenuItem>
                <NavigationMenuItem>
                    <NavigationMenuLink class="cursor-pointer" :class="navigationMenuTriggerStyle()">
                        <CreateProject>
                            Создать проект
                        </CreateProject>
                    </NavigationMenuLink>
                </NavigationMenuItem>
                <!-- <NavigationMenuItem>
                    <NavigationMenuLink href="/docs/introduction" :class="navigationMenuTriggerStyle()">
                        Documentation
                    </NavigationMenuLink>
                </NavigationMenuItem> -->
            </NavigationMenuList>
        </NavigationMenu>
        <ul class="justify-self-end" v-if="!$page.props.auth.user">
            <li>
                <LoginModal>
                    <Button>
                        Вход
                    </Button>
                </LoginModal>
            </li>
        </ul>
    </div>
</template>