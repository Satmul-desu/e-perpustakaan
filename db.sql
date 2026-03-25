--
-- PostgreSQL database dump
--

\restrict 1W5OtkHtZXCcGEsbwq6qs7zrZesTkwl6ftsHA5DurVlTNlwbcR9h8mcn6vgtBVl

-- Dumped from database version 16.13 (Ubuntu 16.13-1.pgdg24.04+1)
-- Dumped by pg_dump version 16.13 (Ubuntu 16.13-1.pgdg24.04+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- Name: cart_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cart_items (
    id bigint NOT NULL,
    cart_id bigint NOT NULL,
    product_id bigint NOT NULL,
    quantity integer DEFAULT 1 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.cart_items OWNER TO postgres;

--
-- Name: cart_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cart_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cart_items_id_seq OWNER TO postgres;

--
-- Name: cart_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cart_items_id_seq OWNED BY public.cart_items.id;


--
-- Name: carts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.carts (
    id bigint NOT NULL,
    user_id bigint,
    session_id character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.carts OWNER TO postgres;

--
-- Name: carts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.carts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.carts_id_seq OWNER TO postgres;

--
-- Name: carts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.carts_id_seq OWNED BY public.carts.id;


--
-- Name: categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(100) NOT NULL,
    slug character varying(100) NOT NULL,
    description text,
    image character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    icon character varying(50)
);


ALTER TABLE public.categories OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categories_id_seq OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: complaints; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.complaints (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    type character varying(255) NOT NULL,
    category character varying(255) NOT NULL,
    subject character varying(255) NOT NULL,
    message text NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    priority character varying(255) DEFAULT 'normal'::character varying NOT NULL,
    order_number character varying(255),
    admin_response text,
    responded_by bigint,
    responded_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.complaints OWNER TO postgres;

--
-- Name: complaints_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.complaints_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.complaints_id_seq OWNER TO postgres;

--
-- Name: complaints_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.complaints_id_seq OWNED BY public.complaints.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: loans; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.loans (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    book_id bigint NOT NULL,
    loan_date date NOT NULL,
    due_date date NOT NULL,
    return_date date,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    admin_notes text,
    approved_by bigint,
    returned_to bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    fine_amount numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    fine_status character varying(255) DEFAULT 'unpaid'::character varying NOT NULL,
    CONSTRAINT loans_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'approved'::character varying, 'borrowed'::character varying, 'returned'::character varying, 'overdue'::character varying, 'cancelled'::character varying])::text[])))
);


ALTER TABLE public.loans OWNER TO postgres;

--
-- Name: loans_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.loans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.loans_id_seq OWNER TO postgres;

--
-- Name: loans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.loans_id_seq OWNED BY public.loans.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: order_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.order_items (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    product_id bigint NOT NULL,
    product_name character varying(255) NOT NULL,
    price numeric(12,2) NOT NULL,
    quantity integer NOT NULL,
    subtotal numeric(15,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.order_items OWNER TO postgres;

--
-- Name: order_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.order_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.order_items_id_seq OWNER TO postgres;

--
-- Name: order_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.order_items_id_seq OWNED BY public.order_items.id;


--
-- Name: orders; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.orders (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    order_number character varying(50) NOT NULL,
    total_amount numeric(15,2) NOT NULL,
    shipping_cost numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    shipping_name character varying(255) NOT NULL,
    shipping_phone character varying(20) NOT NULL,
    shipping_address text NOT NULL,
    payment_method character varying(255),
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    payment_status character varying(255) DEFAULT 'unpaid'::character varying NOT NULL,
    snap_token character varying(255),
    midtrans_order_id character varying(100),
    CONSTRAINT orders_payment_status_check CHECK (((payment_status)::text = ANY ((ARRAY['unpaid'::character varying, 'paid'::character varying, 'failed'::character varying])::text[]))),
    CONSTRAINT orders_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'processing'::character varying, 'shipped'::character varying, 'delivered'::character varying, 'cancelled'::character varying])::text[])))
);


ALTER TABLE public.orders OWNER TO postgres;

--
-- Name: orders_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.orders_id_seq OWNER TO postgres;

--
-- Name: orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.orders_id_seq OWNED BY public.orders.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- Name: payments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.payments (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    midtrans_transaction_id character varying(255),
    midtrans_order_id character varying(255),
    payment_type character varying(50),
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    gross_amount numeric(15,2) NOT NULL,
    snap_token character varying(255),
    payment_url character varying(255),
    expired_at timestamp(0) without time zone,
    paid_at timestamp(0) without time zone,
    raw_response json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT payments_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'success'::character varying, 'failed'::character varying, 'expired'::character varying, 'refunded'::character varying])::text[])))
);


ALTER TABLE public.payments OWNER TO postgres;

--
-- Name: payments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.payments_id_seq OWNER TO postgres;

--
-- Name: payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.payments_id_seq OWNED BY public.payments.id;


--
-- Name: product_images; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.product_images (
    id bigint NOT NULL,
    product_id bigint NOT NULL,
    image_path character varying(255) NOT NULL,
    is_primary boolean DEFAULT false NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.product_images OWNER TO postgres;

--
-- Name: product_images_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.product_images_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.product_images_id_seq OWNER TO postgres;

--
-- Name: product_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.product_images_id_seq OWNED BY public.product_images.id;


--
-- Name: products; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.products (
    id bigint NOT NULL,
    category_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    description text,
    price numeric(12,2) NOT NULL,
    discount_price numeric(12,2),
    stock integer DEFAULT 0 NOT NULL,
    weight integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    is_featured boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.products OWNER TO postgres;

--
-- Name: COLUMN products.weight; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.products.weight IS 'dalam gram';


--
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.products_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.products_id_seq OWNER TO postgres;

--
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.products_id_seq OWNED BY public.products.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(255) DEFAULT 'customer'::character varying NOT NULL,
    avatar character varying(255),
    google_id character varying(255),
    phone character varying(20),
    address text,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['customer'::character varying, 'admin'::character varying])::text[])))
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: wishlists; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.wishlists (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    product_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wishlists OWNER TO postgres;

--
-- Name: wishlists_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.wishlists_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.wishlists_id_seq OWNER TO postgres;

--
-- Name: wishlists_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.wishlists_id_seq OWNED BY public.wishlists.id;


--
-- Name: cart_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cart_items ALTER COLUMN id SET DEFAULT nextval('public.cart_items_id_seq'::regclass);


--
-- Name: carts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carts ALTER COLUMN id SET DEFAULT nextval('public.carts_id_seq'::regclass);


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: complaints id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.complaints ALTER COLUMN id SET DEFAULT nextval('public.complaints_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: loans id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.loans ALTER COLUMN id SET DEFAULT nextval('public.loans_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: order_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.order_items ALTER COLUMN id SET DEFAULT nextval('public.order_items_id_seq'::regclass);


--
-- Name: orders id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.orders ALTER COLUMN id SET DEFAULT nextval('public.orders_id_seq'::regclass);


--
-- Name: payments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payments ALTER COLUMN id SET DEFAULT nextval('public.payments_id_seq'::regclass);


--
-- Name: product_images id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product_images ALTER COLUMN id SET DEFAULT nextval('public.product_images_id_seq'::regclass);


--
-- Name: products id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products ALTER COLUMN id SET DEFAULT nextval('public.products_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: wishlists id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.wishlists ALTER COLUMN id SET DEFAULT nextval('public.wishlists_id_seq'::regclass);


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
e-perpustakaan-cache-admin_stats_7_43	a:13:{s:11:"total_loans";i:4;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:51;s:11:"total_books";i:51;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:4;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773215331
e-perpustakaan-cache-admin_stats_3_22	a:13:{s:11:"total_loans";i:4;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:52;s:11:"total_books";i:52;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:4;s:13:"pending_loans";i:1;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1772767629
e-perpustakaan-cache-admin_stats_3_23	a:13:{s:11:"total_loans";i:4;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:52;s:11:"total_books";i:52;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:4;s:13:"pending_loans";i:1;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1772767693
e-perpustakaan-cache-admin_stats_3_27	a:13:{s:11:"total_loans";i:4;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:52;s:11:"total_books";i:52;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:4;s:13:"pending_loans";i:1;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1772767926
e-perpustakaan-cache-admin_stats_3_33	a:13:{s:11:"total_loans";i:4;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:52;s:11:"total_books";i:52;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:4;s:13:"pending_loans";i:1;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1772768288
e-perpustakaan-cache-admin_stats_3_34	a:13:{s:11:"total_loans";i:4;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:52;s:11:"total_books";i:52;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:4;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1772768389
e-perpustakaan-cache-admin_stats_15_19	a:13:{s:11:"total_loans";i:9;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:9;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773588298
e-perpustakaan-cache-admin_stats_3_35	a:13:{s:11:"total_loans";i:4;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:52;s:11:"total_books";i:52;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:4;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1772768454
e-perpustakaan-cache-admin_stats_3_36	a:13:{s:11:"total_loans";i:4;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:4;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1772768466
e-perpustakaan-cache-5c785c036466adea360111aa28563bfd556b5fba:timer	i:1773890302;	1773890302
e-perpustakaan-cache-gmail@gmail.com|127.0.0.1:timer	i:1772781540;	1772781540
e-perpustakaan-cache-5c785c036466adea360111aa28563bfd556b5fba	i:2;	1773890302
e-perpustakaan-cache-gmail@gmail.com|127.0.0.1	i:2;	1772781540
e-perpustakaan-cache-admin_stats_15_40	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:2;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773243904
e-perpustakaan-cache-admin_stats_0_51	a:13:{s:11:"total_loans";i:4;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:4;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773190572
e-perpustakaan-cache-admin_stats_1_17	a:13:{s:11:"total_loans";i:4;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:4;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773192173
e-perpustakaan-cache-admin_stats_1_49	a:13:{s:11:"total_loans";i:4;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:4;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773194067
e-perpustakaan-cache-admin_stats_15_38	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:2;s:13:"overdue_loans";i:0;s:15:"available_books";i:51;s:11:"total_books";i:51;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773243786
e-perpustakaan-cache-admin_stats_7_42	a:13:{s:11:"total_loans";i:4;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:4;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773215264
e-perpustakaan-cache-admin_stats_7_55	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:2;s:13:"overdue_loans";i:0;s:15:"available_books";i:51;s:11:"total_books";i:51;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773216009
e-perpustakaan-cache-admin_stats_15_39	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:2;s:13:"overdue_loans";i:0;s:15:"available_books";i:51;s:11:"total_books";i:51;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773243897
e-perpustakaan-cache-admin_stats_15_43	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:2;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773244101
e-perpustakaan-cache-admin_stats_15_44	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:2;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773244147
e-perpustakaan-cache-admin_stats_15_11	a:13:{s:11:"total_loans";i:9;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:9;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773587795
e-perpustakaan-cache-admin_stats_2_9	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:2;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773281698
e-perpustakaan-cache-admin_stats_2_13	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:2;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773281899
e-perpustakaan-cache-admin_stats_2_36	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:2;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773283279
e-perpustakaan-cache-admin_stats_4_39	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:2;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773290681
e-perpustakaan-cache-admin_stats_4_43	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:2;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773290882
e-perpustakaan-cache-admin_stats_5_41	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:1;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773294391
e-perpustakaan-cache-admin_stats_7_44	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773388188
e-perpustakaan-cache-admin_stats_1_28	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:1;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773365605
e-perpustakaan-cache-admin_stats_1_47	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773366725
e-perpustakaan-cache-admin_stats_1_51	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773367010
e-perpustakaan-cache-admin_stats_1_52	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773367027
e-perpustakaan-cache-admin_stats_2_2	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773367667
e-perpustakaan-cache-admin_stats_2_5	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773367806
e-perpustakaan-cache-admin_stats_2_6	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773367864
e-perpustakaan-cache-admin_stats_7_45	a:13:{s:11:"total_loans";i:6;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:6;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773388223
e-perpustakaan-cache-admin_stats_7_53	a:13:{s:11:"total_loans";i:8;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:8;s:13:"pending_loans";i:2;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773388720
e-perpustakaan-cache-admin_stats_7_56	a:13:{s:11:"total_loans";i:8;s:12:"active_loans";i:1;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:8;s:13:"pending_loans";i:1;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773388901
e-perpustakaan-cache-admin_stats_8_15	a:13:{s:11:"total_loans";i:8;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:8;s:13:"pending_loans";i:1;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773390047
e-perpustakaan-cache-admin_stats_15_13	a:13:{s:11:"total_loans";i:9;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:9;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773587894
e-perpustakaan-cache-admin_stats_15_24	a:13:{s:11:"total_loans";i:9;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:9;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773588568
e-perpustakaan-cache-admin_stats_16_31	a:13:{s:11:"total_loans";i:9;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:9;s:13:"pending_loans";i:1;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773419805
e-perpustakaan-cache-admin_stats_3_18	a:13:{s:11:"total_loans";i:9;s:12:"active_loans";i:0;s:13:"overdue_loans";i:0;s:15:"available_books";i:50;s:11:"total_books";i:50;s:16:"total_categories";i:8;s:13:"total_members";i:3;s:9:"low_stock";i:0;s:12:"out_of_stock";i:0;s:19:"avg_loans_per_month";i:9;s:13:"pending_loans";i:0;s:17:"complaint_pending";i:0;s:16:"complaint_urgent";i:0;}	1773890589
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: cart_items; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cart_items (id, cart_id, product_id, quantity, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: carts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.carts (id, user_id, session_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categories (id, name, slug, description, image, is_active, created_at, updated_at, icon) FROM stdin;
1	Romance	romance	Novel roman picisan yang penuh kisah cinta dan emosi	galih.jpg	t	2026-03-03 03:09:58	2026-03-03 03:09:58	\N
2	Drama	drama	Kisah dramatis tentang kehidupan dan konflik manusia	jarot.png	t	2026-03-03 03:09:58	2026-03-03 03:09:58	\N
3	Fiksi Remaja	fiksi-remaja	Novel fiksi yang ditulis untuk dan tentang remaja	galih.jpg	t	2026-03-03 03:09:58	2026-03-03 03:09:58	\N
4	Fantasi	fantasi	Dunia magis dan petualangan penuh imajinasi	jarot.png	t	2026-03-03 03:09:58	2026-03-03 03:09:58	\N
5	Horor	horor	Cerita menegangkan penuh kengerian dan suspense	galih.jpg	t	2026-03-03 03:09:58	2026-03-03 03:09:58	\N
6	Politik	politik	Buku tentang dinamika politik dan kekuasaan	jarot.png	t	2026-03-03 03:09:58	2026-03-03 03:09:58	\N
7	Agama	agama	Buku tentang keimanan dan kehidupan spiritual	galih.jpg	t	2026-03-03 03:09:58	2026-03-03 03:09:58	\N
8	Inspiratif	inspiratif	Kisah nyata yang menginspirasi dan memotivasi	jarot.png	t	2026-03-03 03:09:58	2026-03-03 03:09:58	\N
\.


--
-- Data for Name: complaints; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.complaints (id, user_id, type, category, subject, message, status, priority, order_number, admin_response, responded_by, responded_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: loans; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.loans (id, user_id, book_id, loan_date, due_date, return_date, status, notes, admin_notes, approved_by, returned_to, created_at, updated_at, fine_amount, fine_status) FROM stdin;
1	4	2	2026-03-04	2026-03-11	\N	cancelled	\N	Dibatalkan oleh peminjam	\N	\N	2026-03-04 02:18:35	2026-03-05 07:16:48	0.00	unpaid
3	5	5	2026-03-05	2026-03-07	2026-03-05	returned	\nPeminjam mengajukan pengembalian: 2026-03-05 07:50\nPeminjam mengajukan pengembalian: 2026-03-05 07:50\nPeminjam mengajukan pengembalian: 2026-03-05 07:50	\N	4	4	2026-03-05 07:36:03	2026-03-05 07:51:08	0.00	unpaid
2	5	7	2026-03-05	2026-03-07	2026-03-05	returned	\N	\N	4	4	2026-03-05 07:35:16	2026-03-05 07:51:24	0.00	unpaid
4	6	9	2026-03-06	2026-03-13	\N	cancelled	\N	maaf stok barang kami sudah di pesan oleh orang lain sebelum anda..sekian terimakasih banyak	\N	\N	2026-03-06 02:40:26	2026-03-06 03:34:43	0.00	unpaid
6	5	14	2026-03-11	2026-03-13	2026-03-12	returned	\N	\N	4	4	2026-03-11 07:52:35	2026-03-12 04:43:25	0.00	unpaid
5	4	8	2026-03-11	2026-03-13	2026-03-13	returned	\nPeminjam mengajukan pengembalian: 2026-03-11 07:54\nPeminjam mengajukan pengembalian: 2026-03-12 02:38	\N	4	4	2026-03-11 07:48:19	2026-03-13 01:28:45	0.00	unpaid
8	5	6	2026-03-13	2026-03-15	2026-03-13	returned	\nPeminjam mengajukan pengembalian: 2026-03-13 07:56	\N	4	4	2026-03-13 07:52:57	2026-03-13 07:57:16	0.00	unpaid
9	5	4	2026-03-13	2026-03-20	\N	cancelled	\N	Dibatalkan oleh peminjam	\N	\N	2026-03-13 16:29:35	2026-03-13 16:29:41	0.00	unpaid
7	5	3	2026-03-13	2026-03-20	\N	cancelled	\N	Dibatalkan oleh peminjam	\N	\N	2026-03-13 07:52:37	2026-03-15 15:10:48	0.00	unpaid
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_12_16_015724_add_fields_to_users_table	1
5	2025_12_16_020830_create_categories_table	1
6	2025_12_16_020935_create_products_table	1
7	2025_12_16_021036_create_product_images_table	1
8	2025_12_16_021145_create_carts_table	1
9	2025_12_16_021210_create_cart_items_table	1
10	2025_12_16_021254_create_wishlists_table	1
11	2025_12_16_021346_create_orders_table	1
12	2025_12_16_021357_create_order_items_table	1
13	2025_12_16_021449_create_payments_table	1
14	2026_01_05_042635_add_payment_status_to_orders_table	1
15	2026_01_06_053735_add_snap_token_to_orders_table	1
16	2026_01_15_120000_update_shipping_cost_orders	1
17	2026_01_16_000000_fix_order_totals	1
18	2026_01_20_000000_seed_admin_accounts	1
19	2026_01_21_000000_add_midtrans_order_id_to_orders	1
20	2026_01_25_000000_add_icon_to_categories	1
21	2026_01_30_000000_create_complaints_table	1
22	2026_02_01_000000_create_loans_table	1
23	2026_03_15_143225_add_fine_fields_to_loans_table	2
\.


--
-- Data for Name: order_items; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.order_items (id, order_id, product_id, product_name, price, quantity, subtotal, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.orders (id, user_id, order_number, total_amount, shipping_cost, status, shipping_name, shipping_phone, shipping_address, payment_method, notes, created_at, updated_at, payment_status, snap_token, midtrans_order_id) FROM stdin;
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: payments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.payments (id, order_id, midtrans_transaction_id, midtrans_order_id, payment_type, status, gross_amount, snap_token, payment_url, expired_at, paid_at, raw_response, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: product_images; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.product_images (id, product_id, image_path, is_primary, sort_order, created_at, updated_at) FROM stdin;
1	1	books/book2.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
2	2	books/book5.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
3	3	books/book16.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
4	4	books/book17.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
5	5	books/book18.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
6	6	books/book15.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
7	7	books/book19.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
8	8	books/book20.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
9	9	books/book24.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
10	10	books/book25.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
11	11	books/book11.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
12	12	books/book3.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
13	13	books/book4.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
14	14	books/book12.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
15	15	books/book13.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
16	16	books/book6.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
17	17	books/book10.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
18	18	books/book14.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
19	19	books/book23.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
20	20	books/book1.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
21	21	books/book7.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
22	22	books/book9.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
23	23	books/book8.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
24	24	books/book21.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
25	25	books/book22.jpeg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
26	26	books/book26.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
27	27	books/book27.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
28	28	books/book28.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
29	29	books/book29.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
30	30	books/book30.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
31	31	books/book31.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
32	32	books/book32.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
33	33	books/book33.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
34	34	books/book34.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
35	35	books/book35.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
36	36	books/book36.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
37	37	books/book37.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
38	38	books/book38.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
39	39	books/book39.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
40	40	books/book40.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
41	41	books/book41.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
42	42	books/book42.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
43	43	books/book43.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
44	44	books/book44.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
45	45	books/book45.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
46	46	books/book46.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
47	47	books/book47.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
48	48	books/book48.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
49	49	books/book49.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
50	50	books/book50.jpg	t	1	2026-03-03 03:09:58	2026-03-03 03:09:58
\.


--
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.products (id, category_id, name, slug, description, price, discount_price, stock, weight, is_active, is_featured, created_at, updated_at) FROM stdin;
1	1	Twisted Love	twisted-love	Kisah cinta penuh konflik antara Ava dan Alex Volkov, pria dingin dengan masa lalu kelam.	55000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
10	2	Echoes In The Dark	echoes-in-the-dark	Kisah emosional tentang cinta dan rahasia kelam.	50000.00	42000.00	35	320	t	f	2026-03-03 03:09:58	2026-03-03 03:09:58
11	2	Selamat Tinggal	selamat-tinggal	Kisah tentang Sikandar dan kerasnya dunia yang mengajarkan tentang kehidupan.	48000.00	\N	30	310	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
12	3	they both die at the end	they-both-die-at-the-end	Dua remaja, Mateo dan Rufus, bertemu di hari terakhir hidup mereka.	60000.00	50000.00	45	340	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
13	3	Shadow Gril	shadow-gril	Kisah cinta gelap dan emosional tentang gadis pendiam dengan masa lalu kelam.	52000.00	\N	38	300	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
15	4	Bulan	bulan	Lanjutan kisah Raib, Seli, dan Ali di Klan Bulan yang misterius.	70000.00	60000.00	50	400	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
16	4	Matahari	matahari	Petualangan Raib, Seli, dan Ali di dunia paralel Klan Matahari.	65000.00	\N	55	390	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
17	4	Bintang	bintang	Petualangan Raib, Seli, dan Ali di Klan Bintang yang memukau.	55000.00	48000.00	48	360	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
18	4	Harry Potter	harry-potter	Kisah Harry Potter di Hogwarts yang legendaris.	42000.00	\N	120	450	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
19	4	Kesatria, Putri, & Bintang Jatuh	kesatria-putri-bintang-jatuh	Kumpulan cerita dongeng modern yang memukau.	52000.00	\N	42	330	t	f	2026-03-03 03:09:58	2026-03-03 03:09:58
20	1	Night Books	night-books	Novel horor fantasi tentang Alex yang dipaksa menceritakan kisah seram setiap malam.	45000.00	\N	35	290	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
21	6	Negeri Para Bedebah	negeri-para-bedebah	Kisah penuh intrik tentang Thomas dan konspirasi besar di negeri ini.	58000.00	50000.00	28	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
22	6	Hujan	hujan	Kisah cinta dan kehilangan Lail dan Esok di masa depan yang suram.	50000.00	\N	40	310	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
23	7	Kau, Aku dan Sepucuk Angpau Merah	kau-aku-sepucuk-angpau-merah	Kisah sederhana tentang persahabatan, cinta, dan keikhlasan.	62000.00	\N	32	320	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
24	8	Ranah 3 Warna	ranah-3-warna	Perjalanan Alif Fikri mengejar mimpi di perantauan.	55000.00	48000.00	50	360	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
25	8	Rantau 1 Muara	rantau-1-muara	Penutup kisah Alif Fikri di perantauan yang menginspirasi.	48000.00	\N	45	340	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
26	1	Binding	binding	Binding-Chloe Walsh – Cerita emosional tentang trauma, ikatan batin, dan perjuangan menerima cinta di tengah luka dalam. 🖤🤍	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
27	4	Aprendiz del Villano	aprendiz-del-villano	Aprendiz del Villano-Hannah Nicole Maehrer – Kisah fantasi gelap tentang seorang murid yang belajar dari penjahat legendaris, di antara humor, bahaya, dan dilema moral. 😈📘	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
28	1	Twisted Lies	twisted-lies	Twisted Lies-Ana Huang – Romansa penuh intrik antara kebohongan, kekuasaan, dan cinta yang tumbuh di atas rahasia berbahaya. 🕶️❤️	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
29	1	Twisted Hate	twisted-hate	Twisted Hate-Ana Huang – Cerita enemies-to-lovers yang penuh emosi, konflik panas, dan ketertarikan yang sulit dihindari. 🔥💢	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
30	1	Twisted Games	twisted-games	Twisted Games-Ana Huang – Romansa kerajaan modern tentang kewajiban, cinta terlarang, dan pilihan antara hati atau tahta. 👑💔	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
31	1	The Sweetest Oblivion	the-sweetest-oblivion	The Sweetest Oblivion-Danielle Lori – Kisah cinta mafia yang intens, berbahaya, dan penuh ketegangan antara gairah dan kesetiaan. 🖤🔫	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
32	1	Hooked	hooked	Hooked-Emily McIntire – Interpretasi gelap romansa tentang obsesi, balas dendam, dan cinta yang tak sehat namun memikat. 🪝🖤	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
33	1	Crossed	crossed	Crossed-Emily McIntire – Cerita penuh konflik batin tentang pilihan moral, pengkhianatan, dan cinta di sisi tergelap manusia. ⚔️❤️	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
34	1	Scarred	scarred	Scarred-Emily McIntire – Novel ini mengeksplorasi luka emosional dan fisik, serta cinta yang tumbuh dari trauma dan rasa sakit. 🩸💔	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
35	4	Fourth Wing	fourth-wing	Fourth Wing-Rebecca Yarros – Fantasi epik tentang akademi naga, peperangan, dan perjuangan seorang gadis bertahan di dunia mematikan. 🐉🔥	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
36	1	Asylum	asylum	Asylum-Madeleine Roux – Kisah horor psikologis di sebuah rumah sakit jiwa terbengkalai, penuh rahasia dan teror masa lalu. 🏚️😱	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
37	1	Hide and Don’t Seek	hide-and-dont-seek	Hide and Don't Seek-Anica Mrose Rissi – Thriller menegangkan tentang permainan berbahaya yang berubah menjadi mimpi buruk mematikan. 🕯️🔪	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
38	4	Nevernight	nevernight	Nevernight-Jay Kristoff – Fantasi gelap tentang seorang gadis yang dilatih menjadi pembunuh demi membalas dendam pada dunia. 🌑🗡️	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
2	1	Tentang Kamu	tentang-kamu	Kisah pencarian kebenaran tentang kehidupan seorang perempuan bernama Sri Ningsih.	48000.00	42000.00	35	320	t	t	2026-03-03 03:09:58	2026-03-05 07:16:48
7	2	Bandung After Rain	bandung-after-rain	Kisah cinta dan kenangan di Kota Bandung yang romantis.	47000.00	\N	40	300	t	t	2026-03-03 03:09:58	2026-03-05 07:51:24
5	1	Kita & Waktu	kita-waktu	Kisah tentang cinta, waktu, dan perpisahan.	52000.00	\N	30	310	t	t	2026-03-03 03:09:58	2026-03-05 07:51:08
9	2	Dilan 1990	dilan-1990	Kisah cinta unik antara Dilan dan Milea yang ikonik.	45000.00	\N	100	280	t	t	2026-03-03 03:09:58	2026-03-06 03:34:43
8	2	Van Der Wijick	van-der-wijick	Kisah cinta tragis Zainuddin dan Hayati yang mengharukan.	65000.00	55000.00	25	380	t	t	2026-03-03 03:09:58	2026-03-13 01:28:45
14	4	Bumi	bumi	Awal petualangan Raib di dunia paralel yang menakjubkan.	60000.00	\N	60	380	t	t	2026-03-03 03:09:58	2026-03-12 04:43:25
3	1	Fabricante De La Grimas	fabricante-de-la-grimas	Kisah emosional tentang Nica dan Rigel yang penuh misteri.	40000.00	\N	40	300	t	t	2026-03-03 03:09:58	2026-03-15 15:10:48
6	1	Five Feet Apart	five-feet-apart	Kisah cinta Stella dan Will yang berjuang melawan cystic fibrosis.	45000.00	\N	55	290	t	t	2026-03-03 03:09:58	2026-03-13 07:57:16
4	1	Boulevard	boulevard	Kisah cinta pahit-manis antara Hasley dan Luke.	58000.00	50000.00	45	340	t	f	2026-03-03 03:09:58	2026-03-13 16:29:41
39	1	Jack the Ripper	jack-the-ripper	Jack the Ripper-Kerri Maniscalco – Misteri sejarah tentang pembunuh legendaris London, dipadukan dengan kecerdasan dan ketegangan yang mencekam. 🔍🩸	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
40	2	Inherited	inherited	Inherited-Alex Grey – Thriller kriminal tentang warisan kelam yang menyeret tokohnya ke dalam kejahatan dan rahasia berbahaya. 🧬⚠️	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
41	4	Crimson Rogue Cheongfios	crimson-rogue-cheongfios	Crimson Rogue Cheongfios-Laselle – Kisah fantasi penuh intrik tentang pemberontakan, darah bangsawan, dan kekuatan terlarang. 🩸⚔️	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
42	2	The Death Pact	the-death-pact	The Death Pact-Susan Summers – Novel menegangkan tentang perjanjian berbahaya yang mengikat hidup dan kematian para tokohnya. ☠️📜	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
43	4	Fire in the Sky	fire-in-the-sky	Fire in the Sky-Sophie Jordan – Cerita petualangan fantasi tentang kekuatan api, takdir, dan perjuangan melawan kekuasaan tirani. 🔥🌌	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
44	1	Scarlet Huntress	scarlet-huntress	Scarlet Huntress-Leann Mason – Kisah seorang pemburu wanita yang terjebak antara kewajiban, cinta, dan rahasia dunia magis. 🏹❤️	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
45	2	Cold Lake	cold-lake	Cold Lake-Jeff Carson – Thriller kriminal berlatar kota kecil dengan kasus pembunuhan misterius dan rahasia gelap penduduknya. ❄️🔎	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
46	4	Witch	witch	Witch-Finbar Hawkins – Cerita fantasi tentang penyihir muda yang harus memilih antara kekuatan dan kemanusiaannya. 🧙‍♀️✨	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
47	4	The Uncrowned Queen	the-uncrowned-queen	The Uncrowned Queen-Lucy Steele – Novel fantasi politik tentang perebutan tahta, pengkhianatan, dan seorang ratu tanpa mahkota. 👑⚔️	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
48	4	The Sea Witch	the-sea-witch	The Sea Witch-Eva Leight – Kisah magis berlatar laut tentang penyihir, kutukan, dan cinta yang terlarang. 🌊🧜‍♀️	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
49	4	Of the Dark Moon	of-the-dark-moon	Of the Dark Moon-Melissa Kieran – Fantasi romantis tentang kekuatan bulan gelap, rahasia kuno, dan cinta di tengah kegelapan. 🌑💫	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
50	3	Human	human	Human-Greassy Microwave – Novel distopia reflektif tentang arti menjadi manusia di dunia yang perlahan kehilangan empati. 🤖❤️	50000.00	\N	50	350	t	t	2026-03-03 03:09:58	2026-03-03 03:09:58
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role, avatar, google_id, phone, address) FROM stdin;
1	Admin TB	AdminTB@TokoBuku.com	\N	$2y$12$3Dvx8Xz90UpI1.P7AFAH0euofE.8I0ZUwKGB8mbqRwL8U3J03SAXS	\N	2026-03-03 03:09:56	2026-03-03 03:09:56	admin	\N	\N	\N	\N
2	Admin Toko Buku	adminTb@TokoBuku.com	\N	$2y$12$oMFWdndvzaMvpT2HtYKqnOOqebRqhO7aDTFSIWAg8XKvYTHrntZUK	\N	2026-03-03 03:09:56	2026-03-03 03:09:56	admin	\N	\N	\N	\N
3	Test User	test@example.com	2026-03-03 03:09:57	$2y$12$rU8F8iLFo3ZFE7Bbfs4Lweutni0Myxrsk7Sk6TY7NEVDf82v2As3O	B3B0fnhtcr	2026-03-03 03:09:57	2026-03-03 03:09:57	customer	\N	\N	\N	\N
6	Sat Mul	satmul1@gmail.com	\N	$2y$12$0.NgpMl.4t67dVFkzVors.uIRRMAtc9/B6b3V9MX6mxLqXHExZjIq	\N	2026-03-06 02:40:13	2026-03-06 02:40:13	customer	\N	\N	\N	\N
5	Sat Mul	satmul@gmail.com	\N	$2y$12$.cFLSI3Vaf6df36I32JVce89fqAqpfoJn1zy9kHod71CAcGx75wbO	JbiMV4pp8AOe9eIB8toftAYLtExsom4uErqTqZaBrJFqN5F5N6xvIXCFvowL	2026-03-05 02:12:12	2026-03-13 03:06:56	customer	\N	\N	\N	\N
4	Admin	admin@example.com	2026-03-03 03:09:58	$2y$12$Blax1bOa/tO6.nLslzPlluOrK8prIcC5o0OPLCobDmc88cqitWraS	zw744rkuGuW3bwN1i6ZExAKPcjBJK6TJwr4jmsKrEZDPoOAq9eXPRrSUmpiz	2026-03-03 03:09:58	2026-03-13 02:06:36	admin	\N	\N	082129939458	\N
\.


--
-- Data for Name: wishlists; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.wishlists (id, user_id, product_id, created_at, updated_at) FROM stdin;
4	4	15	2026-03-13 16:37:27	2026-03-13 16:37:27
\.


--
-- Name: cart_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cart_items_id_seq', 1, false);


--
-- Name: carts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carts_id_seq', 1, false);


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categories_id_seq', 8, true);


--
-- Name: complaints_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.complaints_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: loans_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.loans_id_seq', 9, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 23, true);


--
-- Name: order_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.order_items_id_seq', 1, false);


--
-- Name: orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.orders_id_seq', 1, false);


--
-- Name: payments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.payments_id_seq', 1, false);


--
-- Name: product_images_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.product_images_id_seq', 54, true);


--
-- Name: products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.products_id_seq', 55, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 6, true);


--
-- Name: wishlists_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.wishlists_id_seq', 4, true);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: cart_items cart_items_cart_id_product_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cart_items
    ADD CONSTRAINT cart_items_cart_id_product_id_unique UNIQUE (cart_id, product_id);


--
-- Name: cart_items cart_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cart_items
    ADD CONSTRAINT cart_items_pkey PRIMARY KEY (id);


--
-- Name: carts carts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carts
    ADD CONSTRAINT carts_pkey PRIMARY KEY (id);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: categories categories_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_slug_unique UNIQUE (slug);


--
-- Name: complaints complaints_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.complaints
    ADD CONSTRAINT complaints_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: loans loans_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.loans
    ADD CONSTRAINT loans_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: order_items order_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_pkey PRIMARY KEY (id);


--
-- Name: orders orders_order_number_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_order_number_unique UNIQUE (order_number);


--
-- Name: orders orders_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: payments payments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_pkey PRIMARY KEY (id);


--
-- Name: product_images product_images_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product_images
    ADD CONSTRAINT product_images_pkey PRIMARY KEY (id);


--
-- Name: products products_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);


--
-- Name: products products_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_slug_unique UNIQUE (slug);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_google_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_google_id_unique UNIQUE (google_id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: wishlists wishlists_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.wishlists
    ADD CONSTRAINT wishlists_pkey PRIMARY KEY (id);


--
-- Name: wishlists wishlists_user_id_product_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.wishlists
    ADD CONSTRAINT wishlists_user_id_product_id_unique UNIQUE (user_id, product_id);


--
-- Name: carts_session_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX carts_session_id_index ON public.carts USING btree (session_id);


--
-- Name: categories_is_active_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX categories_is_active_index ON public.categories USING btree (is_active);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: loans_status_due_date_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX loans_status_due_date_index ON public.loans USING btree (status, due_date);


--
-- Name: loans_user_id_status_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX loans_user_id_status_index ON public.loans USING btree (user_id, status);


--
-- Name: orders_created_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX orders_created_at_index ON public.orders USING btree (created_at);


--
-- Name: orders_order_number_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX orders_order_number_index ON public.orders USING btree (order_number);


--
-- Name: orders_status_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX orders_status_index ON public.orders USING btree (status);


--
-- Name: payments_midtrans_transaction_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX payments_midtrans_transaction_id_index ON public.payments USING btree (midtrans_transaction_id);


--
-- Name: payments_status_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX payments_status_index ON public.payments USING btree (status);


--
-- Name: product_images_product_id_is_primary_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX product_images_product_id_is_primary_index ON public.product_images USING btree (product_id, is_primary);


--
-- Name: products_category_id_is_active_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX products_category_id_is_active_index ON public.products USING btree (category_id, is_active);


--
-- Name: products_is_featured_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX products_is_featured_index ON public.products USING btree (is_featured);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: cart_items cart_items_cart_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cart_items
    ADD CONSTRAINT cart_items_cart_id_foreign FOREIGN KEY (cart_id) REFERENCES public.carts(id) ON DELETE CASCADE;


--
-- Name: cart_items cart_items_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cart_items
    ADD CONSTRAINT cart_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: carts carts_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carts
    ADD CONSTRAINT carts_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: complaints complaints_responded_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.complaints
    ADD CONSTRAINT complaints_responded_by_foreign FOREIGN KEY (responded_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: complaints complaints_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.complaints
    ADD CONSTRAINT complaints_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: loans loans_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.loans
    ADD CONSTRAINT loans_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: loans loans_book_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.loans
    ADD CONSTRAINT loans_book_id_foreign FOREIGN KEY (book_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: loans loans_returned_to_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.loans
    ADD CONSTRAINT loans_returned_to_foreign FOREIGN KEY (returned_to) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: loans loans_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.loans
    ADD CONSTRAINT loans_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: order_items order_items_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_order_id_foreign FOREIGN KEY (order_id) REFERENCES public.orders(id) ON DELETE CASCADE;


--
-- Name: order_items order_items_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE RESTRICT;


--
-- Name: orders orders_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: payments payments_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_order_id_foreign FOREIGN KEY (order_id) REFERENCES public.orders(id) ON DELETE CASCADE;


--
-- Name: product_images product_images_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product_images
    ADD CONSTRAINT product_images_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: products products_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- Name: wishlists wishlists_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.wishlists
    ADD CONSTRAINT wishlists_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: wishlists wishlists_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.wishlists
    ADD CONSTRAINT wishlists_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict 1W5OtkHtZXCcGEsbwq6qs7zrZesTkwl6ftsHA5DurVlTNlwbcR9h8mcn6vgtBVl

