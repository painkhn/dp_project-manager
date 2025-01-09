<script setup lang="ts">
import { Project, Tasks } from '@/types';
import axios from 'axios';
import { ref } from 'vue';
import { format } from 'date-fns';
import TaskReportForm from '../tasks/TaskReportForm.vue';
import TaskReportList from '../tasks/TaskReportList.vue';
import WaitingIcon from '../ui/WaitingIcon.vue';
import CompletedIcon from '../ui/CompletedIcon.vue';
import DeleteIcon from '../ui/DeleteIcon.vue';
import DownArrowIcon from '../ui/DownArrowIcon.vue';
import UpArrowIcon from '../ui/UpArrowIcon.vue';
import CalendarIcon from '../ui/CalendarIcon.vue';
import ShowIcon from '@/Components/ui/ShowIcon.vue'
import {
    FwbA,
    FwbTable,
    FwbTableBody,
    FwbTableCell,
    FwbTableHead,
    FwbTableHeadCell,
    FwbTableRow,
} from 'flowbite-vue'
import {
    FwbAccordion,
    FwbAccordionContent,
    FwbAccordionHeader,
    FwbAccordionPanel,
} from 'flowbite-vue'
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog'

const props = defineProps<{
    tasks: Tasks[] | null;
    project: Project;
}>()

const taskDelete = async (taskId: number) => {
    try {
        await axios.delete(route('task.destroy', { id: taskId }));
        location.reload();
    } catch (error) {
        console.error('Ошибка при удалении задачи:', error);
    }
};

const isTaskDetailsVisible = ref<boolean>(false);
const isTaskReportVisible = ref<boolean>(false);
const btn_id = ref<number>();

const taskDetailsToggle = (taskId: number) => {
    btn_id.value = taskId;
}

const closeDetails = (taskId: number) => {
    btn_id.value = undefined;
}

const taskReportToggle = () => {
    isTaskReportVisible.value = !isTaskReportVisible.value;
}

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return format(date, 'dd.MM.yyyy');
};
</script>

<template>
    <div v-if="(props.tasks as Tasks[])?.length > 0" class="my-5">
        <!-- <ul class="flex flex-col gap-5">
            <li v-for="(task, index) in props.tasks" :key="index">
                <div :class="[
                    'w-full px-4 py-2 border-l-4 transition-all hover:bg-white/10 flex flex-col gap-5',
                    { 'bg-green-900/60': task.status === 'completed' }
                ]"
                    v-if="$page.props.auth.user.id === props.project.user.id || $page.props.auth.user.id === task.user_id">
                    <div class="flex items-center">
                        <p class="text-white text-xl">
                            {{ task.title }}
                        </p>
                        <p class="text-white ml-5" v-if="task.status == 'waiting'">
                            <WaitingIcon />
                        </p>
                        <p class="text-white ml-5" v-else>
                            <CompletedIcon />
                        </p>
                        <p class="text-white text-xl ml-auto">
                            {{ task.user.name }}
                        </p>
                        <button @click="taskDelete(task.id)" class="text-white ml-5"
                            v-if="$page.props.auth.user.id === props.project.user.id">
                            <DeleteIcon />
                        </button>
                        <button class="ml-5"
                            v-if="task.status == 'waiting' || $page.props.auth.user.id === props.project.user.id">
                            <DownArrowIcon @click="taskDetailsToggle(task.id)" v-if="btn_id != task.id"
                                :id="`btn-${task.id}`" />
                            <UpArrowIcon v-if="btn_id == task.id" :id="`btn-${task.id}`"
                                @click="closeDetails(task.id)" />
                        </button>
                    </div>
                    <div v-if="btn_id == task.id">
                        <p class="text-white text-lg mb-2">
                            {{ task.description }}
                        </p>
                        <div class="flex items-center gap-2 mb-5">
                            <CalendarIcon />
                            <p class="text-white text-lg">
                                {{ formatDate(task.start_date) }}
                            </p>
                            <span class="text-white">
                                -
                            </span>
                            <p class="text-white text-lg">
                                {{ formatDate(task.end_date) }}
                            </p>
                        </div>
                        <button @click="taskReportToggle()"
                            class="text-white px-4 py-2 border border-white rounded-md transition-all hover:bg-white/10">
                            Отправить отчёт
                        </button>
                        <TaskReportForm :task="task" :isTaskDetailsVisible="isTaskDetailsVisible"
                            v-if="isTaskReportVisible" />
                        <TaskReportList :task="task" :project="props.project" />
                    </div>
                </div>
            </li>
        </ul> -->
        <!-- <fwb-table>
            <fwb-table-head>
                <fwb-table-head-cell>Product name</fwb-table-head-cell>
                <fwb-table-head-cell>Color</fwb-table-head-cell>
                <fwb-table-head-cell>Category</fwb-table-head-cell>
                <fwb-table-head-cell>Price</fwb-table-head-cell>
                <fwb-table-head-cell>
                    <span class="sr-only">Edit</span>
                </fwb-table-head-cell>
            </fwb-table-head>
            <fwb-table-body>
                <fwb-table-row v-for="(task, index) in props.tasks" :key="index">
                    <fwb-table-cell>{{ task.title }}</fwb-table-cell>
                    <fwb-table-cell>{{ task.status }}</fwb-table-cell>
                    <fwb-table-cell>{{ task.user.name }}</fwb-table-cell>
                    <fwb-table-cell></fwb-table-cell>
                    <fwb-table-cell>
                        <fwb-a href="#">
                            Edit
                        </fwb-a>
                    </fwb-table-cell>
                </fwb-table-row>
            </fwb-table-body>
        </fwb-table> -->
        <Table>
            <TableCaption>A list of your recent invoices.</TableCaption>
            <TableHeader>
                <TableRow>
                    <TableHead class="w-[150px]">
                        Название
                    </TableHead>
                    <TableHead>Статус</TableHead>
                    <TableHead class="text-right">Ответственный</TableHead>
                    <TableHead class="text-right">
                        <!--  -->
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="(task, index) in props.tasks" :key="index">
                    <TableCell class="font-medium">
                        {{ task.title }}
                    </TableCell>
                    <TableCell>
                        <p v-if="task.status === 'waiting'">
                            В процессе
                        </p>
                        <p v-else>
                            Выполнено
                        </p>
                    </TableCell>
                    <TableCell class="text-right">{{ task.user.name }}</TableCell>
                    <TableCell class="text-right">
                        <Dialog>
                            <DialogTrigger v-if="task.status == 'waiting' || $page.props.auth.user.id === props.project.user.id">
                                <ShowIcon />
                            </DialogTrigger>
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>{{ task.title }}</DialogTitle>
                                    <DialogDescription>
                                        {{ task.description }}
                                    </DialogDescription>
                                </DialogHeader>

                                <DialogFooter>
                                    Save changes
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
    <div v-else>
        <p class="text-white/90 text-2xl text-center">
            Не добавлено ни одной задачи
        </p>
    </div>
</template>