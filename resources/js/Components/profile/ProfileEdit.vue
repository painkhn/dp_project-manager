<script setup lang="ts">
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import Button from '../ui/button/Button.vue';
import Logout from './Logout.vue';


</script>

<template>
    <div class="pb-5">
        <div class="space-y-6">
            <div class="bg-white dark:bg-transparent shadow sm:rounded-lg">
                <UpdateProfileInformationForm class="max-w-xl" />
            </div>

            <div class="bg-white dark:bg-transparent shadow sm:rounded-lg">
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div class="bg-white dark:bg-transparent shadow sm:rounded-lg">
                <div class="space-y-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        Выйти из аккаунта
                    </h2>
                    <Logout>
                        <Button>
                            Выход
                        </Button>
                    </Logout>
                </div>
            </div>

            <!-- <div class="bg-white dark:bg-transparent shadow sm:rounded-lg">
                <DeleteUserForm class="max-w-xl" />
            </div> -->
        </div>
    </div>
</template>