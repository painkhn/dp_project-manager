export interface User {
    id: number;
    name: string;
    email: string;
    avatar: string | null;
}

export interface Project {
    id: number;
    title: string;
    description: string;
    start_date: Date;
    end_date: Date;
    user: User;
    // projectUsers: ProjectUser[] | null;
    // invitation: ProjectInvitation | null;
}

export interface ProjectInvitation {
    id: number;
    project: Project;
    // user: User;
    inviter: User;
    invitee: User | null;
    status: string;
}

export interface ProjectUser {
    id: number;
    project: Project;
    user: User;
    user_id: number;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> =
    T & {
        auth: {
            user: User;
        };
    };
