create table provincia
(
    id_provincia          int auto_increment
        primary key,
    nombre_provincia      varchar(255)                    null,
    capital_provincia     varchar(255)                    null,
    descripcion_provincia varchar(1000)                   null,
    poblacion_provincia   decimal(10, 2) default 0.00     null,
    superficie_provincia  decimal(10, 2)                  null,
    latitud_provincia     decimal(9, 6)  default 0.000000 null,
    longitud_provincia    decimal(9, 6)  default 0.000000 null,
    id_region             int                             null
);

create index provincia_tbl_region_id_region_fk
    on provincia (id_region);

create table tbl_category
(
    cat_id          int auto_increment
        primary key,
    cat_name        varchar(255)         null,
    cat_status      tinyint(1) default 1 null,
    cat_description varchar(255)         null,
    created_at      timestamp            null,
    updated_at      timestamp            null,
    cat_image       varchar(255)         null
);

create table tbl_consulta
(
    id_co                int auto_increment
        primary key,
    id_paciente          int                                null,
    fecha_registro       datetime default CURRENT_TIMESTAMP null,
    motivo               varchar(250)                       null,
    id_historial_clinico int                                null,
    estado               char     default 'A'               null
);

create index tbl_consulta_tbl_historial_clinico_id_historial_clinico_fk
    on tbl_consulta (id_historial_clinico);

create table tbl_enterprise
(
    id_enterprise   int auto_increment
        primary key,
    ruc_en          varchar(13) charset utf8mb3        not null,
    razon_social_en varchar(255) charset utf8mb3       null,
    phone_en        varchar(255)                       null,
    logo_en         varchar(255)                       null,
    created_at      datetime default CURRENT_TIMESTAMP null,
    status_en       char     default 'A'               null
);

create table tbl_historial_clinico
(
    id_historial_clinico                int auto_increment
        primary key,
    antecedentes_patologicos_familiares text                                 null,
    antecedentes_patologicos_personales text                                 null,
    antecedentes_oculares_familiares    text                                 null,
    antecedentes_oculares_personales    text                                 null,
    utiliza_lentes                      tinyint(1) default 0                 null,
    tipo_lente                          varchar(100)                         null,
    fecha_inicio_uso_lentes             date                                 null,
    motivo_consulta                     varchar(250)                         null,
    observaciones                       text                                 null,
    created_at                          datetime   default CURRENT_TIMESTAMP null,
    updated_at                          datetime   default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

create table tbl_contactologia
(
    id_contactologia     int auto_increment
        primary key,
    id_historial_clinico int                                not null,
    fecha_examen         datetime default CURRENT_TIMESTAMP null,
    od_esf               decimal(5, 2)                      null,
    od_cyl               decimal(5, 2)                      null,
    od_eje               smallint                           null,
    od_diametro          decimal(5, 2)                      null,
    od_curva_base        decimal(5, 2)                      null,
    oi_esf               decimal(5, 2)                      null,
    oi_cyl               decimal(5, 2)                      null,
    oi_eje               smallint                           null,
    oi_diametro          decimal(5, 2)                      null,
    oi_curva_base        decimal(5, 2)                      null,
    od_av                varchar(10)                        null,
    oi_av                varchar(10)                        null,
    avcc_ao_lejos        varchar(10)                        null,
    avcc_ao_cerca        varchar(10)                        null,
    tipo_lente           varchar(150)                       null,
    observacion          text                               null,
    created_at           datetime default CURRENT_TIMESTAMP null,
    updated_at           datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    constraint fk_cont_historial
        foreign key (id_historial_clinico) references tbl_historial_clinico (id_historial_clinico)
            on update cascade on delete cascade
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

create index idx_cont_historial
    on tbl_contactologia (id_historial_clinico);

create table tbl_evaluacion_oftalmologica
(
    id_evaluacion_oftalmologica int auto_increment
        primary key,
    id_historial_clinico        int                                not null,
    fecha_examen                datetime default CURRENT_TIMESTAMP null,
    biomicroscopia_od           text                               null,
    biomicroscopia_oi           text                               null,
    tension_od                  decimal(5, 2)                      null,
    tension_oi                  decimal(5, 2)                      null,
    pupilas_od                  text                               null,
    pupilas_oi                  text                               null,
    oftalmoscopia_od            text                               null,
    oftalmoscopia_oi            text                               null,
    schirmer_od                 decimal(5, 2)                      null,
    schirmer_oi                 decimal(5, 2)                      null,
    amsler_od                   text                               null,
    amsler_oi                   text                               null,
    otros                       text                               null,
    observaciones               text                               null,
    created_at                  datetime default CURRENT_TIMESTAMP null,
    updated_at                  datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    constraint fk_eo_historial
        foreign key (id_historial_clinico) references tbl_historial_clinico (id_historial_clinico)
            on update cascade on delete cascade
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

create index idx_eo_historial
    on tbl_evaluacion_oftalmologica (id_historial_clinico);

create table tbl_examen_optometrico
(
    id_examen_optometrico int auto_increment
        primary key,
    id_historial_clinico  int                                not null,
    fecha_examen          datetime default CURRENT_TIMESTAMP null,
    queratometria_od      varchar(50)                        null,
    queratometria_oi      varchar(50)                        null,
    retinoscopia_od       varchar(50)                        null,
    retinoscopia_oi       varchar(50)                        null,
    subjetivo_od          varchar(50)                        null,
    subjetivo_oi          varchar(50)                        null,
    refraccion_comp_od    varchar(50)                        null,
    refraccion_comp_oi    varchar(50)                        null,
    percepcion_simultanea text                               null,
    fusion                text                               null,
    estereopsis           text                               null,
    punto_proximo_conv    varchar(50)                        null,
    cover_test_od         varchar(100)                       null,
    cover_test_oi         varchar(100)                       null,
    vision_colores_od     varchar(50)                        null,
    vision_colores_oi     varchar(50)                        null,
    otros                 varchar(200)                       null,
    observacion           varchar(200)                       null,
    notas                 varchar(200)                       null,
    created_at            datetime default CURRENT_TIMESTAMP null,
    updated_at            datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    constraint fk_exopt_historial
        foreign key (id_historial_clinico) references tbl_historial_clinico (id_historial_clinico)
            on update cascade on delete cascade
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

create index idx_exopt_historial
    on tbl_examen_optometrico (id_historial_clinico);

create table tbl_examenes_preliminares
(
    id_examenes_preliminares int auto_increment
        primary key,
    id_historial_clinico     int                                not null,
    fecha_examen             datetime default CURRENT_TIMESTAMP null,
    observacion_od           text                               null,
    observacion_oi           text                               null,
    av_od                    varchar(10)                        null,
    av_oi                    varchar(10)                        null,
    av_ao                    varchar(10)                        null,
    av_estenop_od            varchar(10)                        null,
    av_estenop_oi            varchar(10)                        null,
    motilidad_1              text                               null,
    hirschberg_1             text                               null,
    purkinje                 text                               null,
    ccv_od                   text                               null,
    ccv_oi                   text                               null,
    motilidad_2              text                               null,
    hirschberg_2             text                               null,
    created_at               datetime default CURRENT_TIMESTAMP null,
    updated_at               datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    constraint fk_ep_historial
        foreign key (id_historial_clinico) references tbl_historial_clinico (id_historial_clinico)
            on update cascade on delete cascade
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

create index idx_ep_historial
    on tbl_examenes_preliminares (id_historial_clinico);

create table tbl_laboratorio
(
    id_laboratorio int auto_increment
        primary key,
    nombre         varchar(150)                       not null,
    categoria      varchar(100)                       null,
    fecha_creacion date                               null,
    id_encargado   int                                null,
    created_at     datetime default CURRENT_TIMESTAMP null,
    updated_at     datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);

create index fk_laboratorio_encargado
    on tbl_laboratorio (id_encargado);

create table tbl_lensometria
(
    id_lensometria       int auto_increment
        primary key,
    id_historial_clinico int                                not null,
    fecha_examen         datetime default CURRENT_TIMESTAMP null,
    od_esf               decimal(5, 2)                      null,
    od_cyl               decimal(5, 2)                      null,
    od_eje               smallint                           null,
    od_add               decimal(4, 2)                      null,
    oi_esf               decimal(5, 2)                      null,
    oi_cyl               decimal(5, 2)                      null,
    oi_eje               smallint                           null,
    oi_add               decimal(4, 2)                      null,
    od_prisma            decimal(5, 2)                      null,
    od_dnp               decimal(5, 2)                      null,
    od_dp                decimal(5, 2)                      null,
    od_alt               decimal(5, 2)                      null,
    oi_prisma            decimal(5, 2)                      null,
    oi_dnp               decimal(5, 2)                      null,
    oi_dp                decimal(5, 2)                      null,
    oi_alt               decimal(5, 2)                      null,
    diseno_lente         varchar(100)                       null,
    material             varchar(100)                       null,
    tratamiento          varchar(100)                       null,
    created_at           datetime default CURRENT_TIMESTAMP null,
    updated_at           datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    constraint fk_lens_historial
        foreign key (id_historial_clinico) references tbl_historial_clinico (id_historial_clinico)
            on update cascade on delete cascade
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

create index idx_lens_historial
    on tbl_lensometria (id_historial_clinico);

create table tbl_permission
(
    perm_id   int auto_increment
        primary key,
    perm_name varchar(255) null
);

create table tbl_region
(
    id_region     int auto_increment
        primary key,
    nombre_region varchar(255) null
);

create table tbl_rol_user
(
    ru_id   int auto_increment
        primary key,
    us_id   int null,
    role_id int null
);

create index tbl_rol_user_tbl_role_rol_id_fk
    on tbl_rol_user (role_id);

create index tbl_rol_user_tbl_user_us_id_fk
    on tbl_rol_user (us_id);

create table tbl_role
(
    rol_id     int auto_increment
        primary key,
    rol_name   varchar(255)     null,
    rol_status char default 'A' null
);

create table tbl_role_permission
(
    rp_id   int auto_increment
        primary key,
    role_id int null,
    perm_id int null
);

create index tbl_role_permission_tbl_permission_perm_id_fk
    on tbl_role_permission (perm_id);

create index tbl_role_permission_tbl_role_rol_id_fk
    on tbl_role_permission (role_id);

create table tbl_rx_final_contactologia
(
    id_rx_final_contactologia int auto_increment
        primary key,
    id_historial_clinico      int                                not null,
    fecha_examen              datetime default CURRENT_TIMESTAMP null,
    od_esf                    decimal(5, 2)                      null,
    od_cyl                    decimal(5, 2)                      null,
    od_eje                    smallint                           null,
    od_diametro               decimal(5, 2)                      null,
    od_curva_base             decimal(5, 2)                      null,
    oi_esf                    decimal(5, 2)                      null,
    oi_cyl                    decimal(5, 2)                      null,
    oi_eje                    smallint                           null,
    oi_diametro               decimal(5, 2)                      null,
    oi_curva_base             decimal(5, 2)                      null,
    od_av                     varchar(10)                        null,
    oi_av                     varchar(10)                        null,
    avcc_ao_lejos             varchar(10)                        null,
    avcc_ao_cerca             varchar(10)                        null,
    tipo_lente                varchar(150)                       null,
    observacion               text                               null,
    created_at                datetime default CURRENT_TIMESTAMP null,
    updated_at                datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    constraint fk_rxfc_historial
        foreign key (id_historial_clinico) references tbl_historial_clinico (id_historial_clinico)
            on update cascade on delete cascade
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

create index idx_rxfc_historial
    on tbl_rx_final_contactologia (id_historial_clinico);

create table tbl_rx_final_lente
(
    id_rx_final_lente    int auto_increment
        primary key,
    id_historial_clinico int                                not null,
    fecha_examen         datetime default CURRENT_TIMESTAMP null,
    od_esf               decimal(5, 2)                      null,
    od_cyl               decimal(5, 2)                      null,
    od_eje               smallint                           null,
    od_add               decimal(4, 2)                      null,
    oi_esf               decimal(5, 2)                      null,
    oi_cyl               decimal(5, 2)                      null,
    oi_eje               smallint                           null,
    oi_add               decimal(4, 2)                      null,
    od_prisma            decimal(5, 2)                      null,
    od_dnp               decimal(5, 2)                      null,
    od_dp                decimal(5, 2)                      null,
    od_alt               decimal(5, 2)                      null,
    od_av_lejos          varchar(10)                        null,
    od_av_cerca          varchar(10)                        null,
    oi_prisma            decimal(5, 2)                      null,
    oi_dnp               decimal(5, 2)                      null,
    oi_dp                decimal(5, 2)                      null,
    oi_alt               decimal(5, 2)                      null,
    oi_av_lejos          varchar(10)                        null,
    oi_av_cerca          varchar(10)                        null,
    avcc_ao_lejos        varchar(10)                        null,
    avcc_ao_cerca        varchar(10)                        null,
    diseno_lente         varchar(100)                       null,
    tratamiento          varchar(100)                       null,
    material             varchar(100)                       null,
    diagnostico          text                               null,
    recomendaciones      text                               null,
    observaciones        text                               null,
    created_at           datetime default CURRENT_TIMESTAMP null,
    updated_at           datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    constraint fk_rxfl_historial
        foreign key (id_historial_clinico) references tbl_historial_clinico (id_historial_clinico)
            on update cascade on delete cascade
)
    engine = InnoDB
    collate = utf8mb4_unicode_ci;

create index idx_rxfl_historial
    on tbl_rx_final_lente (id_historial_clinico);

create table tbl_sucursal
(
    id_sucursal int auto_increment
        primary key,
    empresa_id  int                          null,
    ciudad_id   int                          null,
    nombre      varchar(255) charset utf8mb3 null,
    direccion   varchar(255) charset utf8mb3 null,
    telefono    varchar(100) charset utf8mb3 null,
    correo      varchar(255) charset utf8mb3 null,
    estado      char default 'A'             null
);

create index tbl_sucursal_id_sucursal_index
    on tbl_sucursal (id_sucursal);

create index tbl_sucursal_provincia_id_provincia_fk
    on tbl_sucursal (ciudad_id);

create index tbl_sucursal_tbl_enterprise_id_enterprise_fk
    on tbl_sucursal (empresa_id);

create table tbl_token
(
    token_id   int auto_increment
        primary key,
    usu_id     int                        null,
    token      varchar(255)               null,
    created_at timestamp  default (now()) null,
    expires_at timestamp                  null,
    is_revoked tinyint(1) default 0       null
);

create index tbl_token_tbl_user_us_id_fk
    on tbl_token (usu_id);

create table tbl_user
(
    us_id            int auto_increment
        primary key,
    us_name          varchar(255)              null,
    us_lastName      varchar(255)              null,
    us_image         varchar(255)              null,
    us_address       varchar(255)              null,
    us_dni           varchar(10)               null,
    us_first_phone   varchar(10)               null,
    us_second_phone  varchar(10)               null,
    created_at       timestamp default (now()) null,
    us_email         varchar(255)              null,
    us_password      varchar(255)              null,
    us_status        char      default 'N'     null,
    updated_at       timestamp                 null,
    email_verfied_at timestamp                 null,
    google_id        varchar(255)              null,
    date_of_birth    timestamp                 null,
    is_web           bit                       null
);

