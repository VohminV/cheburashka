--
-- PostgreSQL database dump
--

\restrict 64jbpSu045ejqze6R1lb6ZgnWmWRH6qqePKVcxtfcHsLIT6mbl0F9GQFus8Ogwy

-- Dumped from database version 17.7
-- Dumped by pg_dump version 17.7

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: pg_database_owner
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO pg_database_owner;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: pg_database_owner
--

COMMENT ON SCHEMA public IS 'standard public schema';


--
-- Name: generate_issue_key(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.generate_issue_key() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    proj_key TEXT;
    next_id INTEGER;
BEGIN
    -- Если issue_key уже задан — ничего не делаем
    IF NEW.issue_key IS NOT NULL AND NEW.issue_key != '' THEN
        RETURN NEW;
    END IF;

    -- Получаем project_key
    SELECT project_key INTO proj_key
    FROM project
    WHERE id = NEW.project_id;

    IF proj_key IS NULL THEN
        RAISE EXCEPTION 'Project with id % not found', NEW.project_id;
    END IF;

    -- Получаем следующий id
    next_id := nextval(pg_get_serial_sequence('issue', 'id'));

    -- Формируем ключ
    NEW.issue_key := proj_key || '-' || next_id;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public.generate_issue_key() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: auth_assignment; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.auth_assignment (
    item_name character varying(64) NOT NULL,
    user_id character varying(64) NOT NULL,
    created_at integer
);


ALTER TABLE public.auth_assignment OWNER TO postgres;

--
-- Name: auth_item; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.auth_item (
    name character varying(64) NOT NULL,
    type integer NOT NULL,
    description text,
    rule_name character varying(64),
    data bytea,
    created_at integer,
    updated_at integer
);


ALTER TABLE public.auth_item OWNER TO postgres;

--
-- Name: auth_item_child; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.auth_item_child (
    parent character varying(64) NOT NULL,
    child character varying(64) NOT NULL
);


ALTER TABLE public.auth_item_child OWNER TO postgres;

--
-- Name: auth_rule; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.auth_rule (
    name character varying(64) NOT NULL,
    data bytea,
    created_at integer,
    updated_at integer
);


ALTER TABLE public.auth_rule OWNER TO postgres;

--
-- Name: issue; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.issue (
    id integer NOT NULL,
    project_id integer NOT NULL,
    issue_type_id integer NOT NULL,
    status_id integer NOT NULL,
    issue_key character varying(20),
    summary character varying(255) NOT NULL,
    description text,
    assignee_id integer,
    reporter_id integer NOT NULL,
    parent_issue_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    priority_id integer,
    resolution_id integer
);


ALTER TABLE public.issue OWNER TO postgres;

--
-- Name: issue_comment; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.issue_comment (
    id integer NOT NULL,
    issue_id integer NOT NULL,
    user_id integer NOT NULL,
    body text NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.issue_comment OWNER TO postgres;

--
-- Name: issue_comment_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.issue_comment_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.issue_comment_id_seq OWNER TO postgres;

--
-- Name: issue_comment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.issue_comment_id_seq OWNED BY public.issue_comment.id;


--
-- Name: issue_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.issue_history (
    id integer NOT NULL,
    issue_id integer NOT NULL,
    user_id integer NOT NULL,
    field character varying(64) NOT NULL,
    old_value text,
    new_value text,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.issue_history OWNER TO postgres;

--
-- Name: issue_history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.issue_history_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.issue_history_id_seq OWNER TO postgres;

--
-- Name: issue_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.issue_history_id_seq OWNED BY public.issue_history.id;


--
-- Name: issue_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.issue_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.issue_id_seq OWNER TO postgres;

--
-- Name: issue_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.issue_id_seq OWNED BY public.issue.id;


--
-- Name: issue_priority; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.issue_priority (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    color character varying(7) DEFAULT '#cccccc'::character varying
);


ALTER TABLE public.issue_priority OWNER TO postgres;

--
-- Name: issue_priority_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.issue_priority_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.issue_priority_id_seq OWNER TO postgres;

--
-- Name: issue_priority_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.issue_priority_id_seq OWNED BY public.issue_priority.id;


--
-- Name: issue_resolution; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.issue_resolution (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    description text
);


ALTER TABLE public.issue_resolution OWNER TO postgres;

--
-- Name: issue_resolution_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.issue_resolution_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.issue_resolution_id_seq OWNER TO postgres;

--
-- Name: issue_resolution_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.issue_resolution_id_seq OWNED BY public.issue_resolution.id;


--
-- Name: issue_status; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.issue_status (
    id integer NOT NULL,
    tenant_id integer NOT NULL,
    name character varying(50) NOT NULL,
    category character varying(20) DEFAULT 'TODO'::character varying NOT NULL
);


ALTER TABLE public.issue_status OWNER TO postgres;

--
-- Name: issue_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.issue_status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.issue_status_id_seq OWNER TO postgres;

--
-- Name: issue_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.issue_status_id_seq OWNED BY public.issue_status.id;


--
-- Name: issue_type; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.issue_type (
    id integer NOT NULL,
    tenant_id integer NOT NULL,
    name character varying(50) NOT NULL,
    description text,
    icon character varying(50)
);


ALTER TABLE public.issue_type OWNER TO postgres;

--
-- Name: issue_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.issue_type_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.issue_type_id_seq OWNER TO postgres;

--
-- Name: issue_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.issue_type_id_seq OWNED BY public.issue_type.id;


--
-- Name: issue_watcher; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.issue_watcher (
    issue_id integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.issue_watcher OWNER TO postgres;

--
-- Name: migration; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migration (
    version character varying(180) NOT NULL,
    apply_time integer
);


ALTER TABLE public.migration OWNER TO postgres;

--
-- Name: project; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.project (
    id integer NOT NULL,
    tenant_id integer NOT NULL,
    name character varying(255) NOT NULL,
    project_key character varying(10) NOT NULL,
    description text,
    lead_user_id integer,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.project OWNER TO postgres;

--
-- Name: project_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.project_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.project_id_seq OWNER TO postgres;

--
-- Name: project_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.project_id_seq OWNED BY public.project.id;


--
-- Name: tenant; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tenant (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.tenant OWNER TO postgres;

--
-- Name: tenant_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tenant_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tenant_id_seq OWNER TO postgres;

--
-- Name: tenant_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tenant_id_seq OWNED BY public.tenant.id;


--
-- Name: user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    auth_key character varying(32) NOT NULL,
    password_hash character varying(255) NOT NULL,
    password_reset_token character varying(255),
    email character varying(255) NOT NULL,
    status smallint DEFAULT 10 NOT NULL,
    created_at integer NOT NULL,
    updated_at integer NOT NULL,
    verification_token character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public."user" OWNER TO postgres;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_id_seq OWNER TO postgres;

--
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_id_seq OWNED BY public."user".id;


--
-- Name: issue id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue ALTER COLUMN id SET DEFAULT nextval('public.issue_id_seq'::regclass);


--
-- Name: issue_comment id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_comment ALTER COLUMN id SET DEFAULT nextval('public.issue_comment_id_seq'::regclass);


--
-- Name: issue_history id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_history ALTER COLUMN id SET DEFAULT nextval('public.issue_history_id_seq'::regclass);


--
-- Name: issue_priority id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_priority ALTER COLUMN id SET DEFAULT nextval('public.issue_priority_id_seq'::regclass);


--
-- Name: issue_resolution id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_resolution ALTER COLUMN id SET DEFAULT nextval('public.issue_resolution_id_seq'::regclass);


--
-- Name: issue_status id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_status ALTER COLUMN id SET DEFAULT nextval('public.issue_status_id_seq'::regclass);


--
-- Name: issue_type id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_type ALTER COLUMN id SET DEFAULT nextval('public.issue_type_id_seq'::regclass);


--
-- Name: project id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project ALTER COLUMN id SET DEFAULT nextval('public.project_id_seq'::regclass);


--
-- Name: tenant id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tenant ALTER COLUMN id SET DEFAULT nextval('public.tenant_id_seq'::regclass);


--
-- Name: user id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user" ALTER COLUMN id SET DEFAULT nextval('public.user_id_seq'::regclass);


--
-- Data for Name: auth_assignment; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.auth_assignment (item_name, user_id, created_at) FROM stdin;
\.


--
-- Data for Name: auth_item; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.auth_item (name, type, description, rule_name, data, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: auth_item_child; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.auth_item_child (parent, child) FROM stdin;
\.


--
-- Data for Name: auth_rule; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.auth_rule (name, data, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: issue; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.issue (id, project_id, issue_type_id, status_id, issue_key, summary, description, assignee_id, reporter_id, parent_issue_id, created_at, updated_at, priority_id, resolution_id) FROM stdin;
1	1	2	3	CHEB-1	Проверка		\N	2	\N	2025-12-08 08:51:18	2025-12-08 14:51:29.365284	3	\N
2	1	3	1	CHEB-2	йцу	йцу	2	2	\N	2025-12-10 09:12:30	2025-12-10 09:12:30	1	\N
\.


--
-- Data for Name: issue_comment; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.issue_comment (id, issue_id, user_id, body, created_at, updated_at) FROM stdin;
1	1	2	Проверка	2025-12-08 10:19:34	2025-12-08 16:19:34.212299
\.


--
-- Data for Name: issue_history; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.issue_history (id, issue_id, user_id, field, old_value, new_value, created_at) FROM stdin;
\.


--
-- Data for Name: issue_priority; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.issue_priority (id, name, color) FROM stdin;
1	Низкий	#cccccc
2	Средний	#cccccc
3	Высокий	#cccccc
4	Критический	#cccccc
\.


--
-- Data for Name: issue_resolution; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.issue_resolution (id, name, description) FROM stdin;
1	Не исправлять	Задача не будет исправляться.
2	Отклонено	Запрос отклонён.
3	Дубликат	Задача уже существует.
4	Выполнено	Задача успешно завершена.
5	Исправлено	Проблема устранена.
6	Невоспроизводимо	Невозможно воспроизвести описанную проблему.
\.


--
-- Data for Name: issue_status; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.issue_status (id, tenant_id, name, category) FROM stdin;
1	1	To Do	TODO
2	1	In Progress	IN_PROGRESS
3	1	Done	DONE
\.


--
-- Data for Name: issue_type; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.issue_type (id, tenant_id, name, description, icon) FROM stdin;
1	1	Task	\N	task
2	1	Bug	\N	bug
3	1	Story	\N	story
4	1	Epic	\N	epic
\.


--
-- Data for Name: issue_watcher; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.issue_watcher (issue_id, user_id) FROM stdin;
1	2
2	2
\.


--
-- Data for Name: migration; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migration (version, apply_time) FROM stdin;
m000000_000000_base	1765156429
m130524_201442_init	1765156431
m190124_110200_add_verification_token_column_to_user_table	1765156431
\.


--
-- Data for Name: project; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.project (id, tenant_id, name, project_key, description, lead_user_id, created_at) FROM stdin;
1	1	Чебурашка	CHEB	\N	2	2025-12-08 09:35:04.156388
\.


--
-- Data for Name: tenant; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tenant (id, name, created_at) FROM stdin;
1	Default Organization	2025-12-08 09:35:04.156388
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."user" (id, username, auth_key, password_hash, password_reset_token, email, status, created_at, updated_at, verification_token) FROM stdin;
2	cheburashka	EfACZn9P2QDtdWpq0bf_TZEwnw9unjNT	$2y$13$J9idLDkQd1PrPi6LyorffeR3aja4oj0diLTsYuANM1DtqjOB4nhwC	\N	cheburashka@example.com	10	1765159699	1765159699	\N
\.


--
-- Name: issue_comment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.issue_comment_id_seq', 1, true);


--
-- Name: issue_history_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.issue_history_id_seq', 1, false);


--
-- Name: issue_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.issue_id_seq', 3, true);


--
-- Name: issue_priority_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.issue_priority_id_seq', 4, true);


--
-- Name: issue_resolution_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.issue_resolution_id_seq', 6, true);


--
-- Name: issue_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.issue_status_id_seq', 3, true);


--
-- Name: issue_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.issue_type_id_seq', 4, true);


--
-- Name: project_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.project_id_seq', 1, true);


--
-- Name: tenant_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tenant_id_seq', 1, true);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_id_seq', 2, true);


--
-- Name: auth_assignment auth_assignment_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auth_assignment
    ADD CONSTRAINT auth_assignment_pkey PRIMARY KEY (item_name, user_id);


--
-- Name: auth_item_child auth_item_child_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auth_item_child
    ADD CONSTRAINT auth_item_child_pkey PRIMARY KEY (parent, child);


--
-- Name: auth_item auth_item_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auth_item
    ADD CONSTRAINT auth_item_pkey PRIMARY KEY (name);


--
-- Name: auth_rule auth_rule_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auth_rule
    ADD CONSTRAINT auth_rule_pkey PRIMARY KEY (name);


--
-- Name: issue_comment issue_comment_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_comment
    ADD CONSTRAINT issue_comment_pkey PRIMARY KEY (id);


--
-- Name: issue_history issue_history_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_history
    ADD CONSTRAINT issue_history_pkey PRIMARY KEY (id);


--
-- Name: issue issue_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue
    ADD CONSTRAINT issue_pkey PRIMARY KEY (id);


--
-- Name: issue_priority issue_priority_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_priority
    ADD CONSTRAINT issue_priority_pkey PRIMARY KEY (id);


--
-- Name: issue issue_project_id_issue_key_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue
    ADD CONSTRAINT issue_project_id_issue_key_key UNIQUE (project_id, issue_key);


--
-- Name: issue_resolution issue_resolution_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_resolution
    ADD CONSTRAINT issue_resolution_pkey PRIMARY KEY (id);


--
-- Name: issue_status issue_status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_status
    ADD CONSTRAINT issue_status_pkey PRIMARY KEY (id);


--
-- Name: issue_status issue_status_tenant_id_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_status
    ADD CONSTRAINT issue_status_tenant_id_name_key UNIQUE (tenant_id, name);


--
-- Name: issue_type issue_type_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_type
    ADD CONSTRAINT issue_type_pkey PRIMARY KEY (id);


--
-- Name: issue_type issue_type_tenant_id_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_type
    ADD CONSTRAINT issue_type_tenant_id_name_key UNIQUE (tenant_id, name);


--
-- Name: issue_watcher issue_watcher_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_watcher
    ADD CONSTRAINT issue_watcher_pkey PRIMARY KEY (issue_id, user_id);


--
-- Name: migration migration_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (version);


--
-- Name: project project_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT project_pkey PRIMARY KEY (id);


--
-- Name: project project_tenant_id_project_key_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT project_tenant_id_project_key_key UNIQUE (tenant_id, project_key);


--
-- Name: tenant tenant_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tenant
    ADD CONSTRAINT tenant_pkey PRIMARY KEY (id);


--
-- Name: user user_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_email_key UNIQUE (email);


--
-- Name: user user_password_reset_token_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_password_reset_token_key UNIQUE (password_reset_token);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: user user_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_username_key UNIQUE (username);


--
-- Name: idx_comment_issue_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_comment_issue_id ON public.issue_comment USING btree (issue_id);


--
-- Name: idx_issue_assignee_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_issue_assignee_id ON public.issue USING btree (assignee_id);


--
-- Name: idx_issue_project_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_issue_project_id ON public.issue USING btree (project_id);


--
-- Name: idx_project_tenant_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_project_tenant_id ON public.project USING btree (tenant_id);


--
-- Name: issue trg_issue_generate_key; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_issue_generate_key BEFORE INSERT ON public.issue FOR EACH ROW EXECUTE FUNCTION public.generate_issue_key();


--
-- Name: auth_assignment auth_assignment_item_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auth_assignment
    ADD CONSTRAINT auth_assignment_item_name_fkey FOREIGN KEY (item_name) REFERENCES public.auth_item(name) ON DELETE CASCADE;


--
-- Name: auth_item_child auth_item_child_child_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auth_item_child
    ADD CONSTRAINT auth_item_child_child_fkey FOREIGN KEY (child) REFERENCES public.auth_item(name) ON DELETE CASCADE;


--
-- Name: auth_item_child auth_item_child_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auth_item_child
    ADD CONSTRAINT auth_item_child_parent_fkey FOREIGN KEY (parent) REFERENCES public.auth_item(name) ON DELETE CASCADE;


--
-- Name: issue issue_assignee_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue
    ADD CONSTRAINT issue_assignee_id_fkey FOREIGN KEY (assignee_id) REFERENCES public."user"(id) ON DELETE SET NULL;


--
-- Name: issue_comment issue_comment_issue_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_comment
    ADD CONSTRAINT issue_comment_issue_id_fkey FOREIGN KEY (issue_id) REFERENCES public.issue(id) ON DELETE CASCADE;


--
-- Name: issue_comment issue_comment_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_comment
    ADD CONSTRAINT issue_comment_user_id_fkey FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: issue_history issue_history_issue_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_history
    ADD CONSTRAINT issue_history_issue_id_fkey FOREIGN KEY (issue_id) REFERENCES public.issue(id) ON DELETE CASCADE;


--
-- Name: issue_history issue_history_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_history
    ADD CONSTRAINT issue_history_user_id_fkey FOREIGN KEY (user_id) REFERENCES public."user"(id) ON DELETE RESTRICT;


--
-- Name: issue issue_issue_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue
    ADD CONSTRAINT issue_issue_type_id_fkey FOREIGN KEY (issue_type_id) REFERENCES public.issue_type(id);


--
-- Name: issue issue_parent_issue_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue
    ADD CONSTRAINT issue_parent_issue_id_fkey FOREIGN KEY (parent_issue_id) REFERENCES public.issue(id) ON DELETE CASCADE;


--
-- Name: issue issue_priority_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue
    ADD CONSTRAINT issue_priority_id_fkey FOREIGN KEY (priority_id) REFERENCES public.issue_priority(id);


--
-- Name: issue issue_project_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue
    ADD CONSTRAINT issue_project_id_fkey FOREIGN KEY (project_id) REFERENCES public.project(id) ON DELETE CASCADE;


--
-- Name: issue issue_reporter_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue
    ADD CONSTRAINT issue_reporter_id_fkey FOREIGN KEY (reporter_id) REFERENCES public."user"(id);


--
-- Name: issue issue_resolution_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue
    ADD CONSTRAINT issue_resolution_id_fkey FOREIGN KEY (resolution_id) REFERENCES public.issue_resolution(id) ON DELETE SET NULL;


--
-- Name: issue issue_status_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue
    ADD CONSTRAINT issue_status_id_fkey FOREIGN KEY (status_id) REFERENCES public.issue_status(id);


--
-- Name: issue_status issue_status_tenant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_status
    ADD CONSTRAINT issue_status_tenant_id_fkey FOREIGN KEY (tenant_id) REFERENCES public.tenant(id) ON DELETE CASCADE;


--
-- Name: issue_type issue_type_tenant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_type
    ADD CONSTRAINT issue_type_tenant_id_fkey FOREIGN KEY (tenant_id) REFERENCES public.tenant(id) ON DELETE CASCADE;


--
-- Name: issue_watcher issue_watcher_issue_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_watcher
    ADD CONSTRAINT issue_watcher_issue_id_fkey FOREIGN KEY (issue_id) REFERENCES public.issue(id) ON DELETE CASCADE;


--
-- Name: issue_watcher issue_watcher_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.issue_watcher
    ADD CONSTRAINT issue_watcher_user_id_fkey FOREIGN KEY (user_id) REFERENCES public."user"(id) ON DELETE CASCADE;


--
-- Name: project project_tenant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT project_tenant_id_fkey FOREIGN KEY (tenant_id) REFERENCES public.tenant(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict 64jbpSu045ejqze6R1lb6ZgnWmWRH6qqePKVcxtfcHsLIT6mbl0F9GQFus8Ogwy

