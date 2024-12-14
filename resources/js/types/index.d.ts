export interface User {
    id: number;
    name: string;
    email: string;
    avatar: string | null;
}

export interface Project {
    id: number,
    title: string,
    description: string,
    start_date: Date,
    end_date: Date,
    user: User
}

export interface ProjectInvitation {
    id: number;
    project: Project;
    inviter: User;
    status: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> =
    T & {
        auth: {
            user: User;
        };
    };
