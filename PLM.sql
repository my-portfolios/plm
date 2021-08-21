-- MySQL dump 10.16  Distrib 10.2.14-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: PLM
-- ------------------------------------------------------
-- Server version	10.2.14-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `PLM_BOARD`
--

DROP TABLE IF EXISTS `PLM_BOARD`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_BOARD` (
  `BOARD_ID` varchar(255) NOT NULL COMMENT '게시판ID',
  `BOARD` varchar(255) DEFAULT NULL COMMENT 'board이름',
  `BOARD_TITLE` varchar(255) DEFAULT NULL COMMENT '제목',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  `BOARD_NOTICE` text DEFAULT NULL,
  `BOARD_AUTH` int(11) DEFAULT NULL COMMENT '접근 권한 레벨 ( 1 or 2 or 3 ) 1:사용자,작업자,관리자 , 2:작업자,관리자 , 3:관리자',
  `BOARD_PER` int(11) DEFAULT NULL COMMENT '읽기,쓰기 레벨 ( 1 or 2 or 3 ) 읽기쓰기 ,로 구분 (안씀)',
  `BOARD_READ_AUTH` int(11) DEFAULT NULL COMMENT '읽기 권한 레벨  ( 1 or 2 or 3 ) 1:사용자,작업자,관리자 , 2:작업자,관리자 , 3:관리자',
  `BOARD_WRITE_AUTH` int(11) DEFAULT NULL COMMENT '쓰기 권한 레벨  ( 1 or 2 or 3 ) 1:사용자,작업자,관리자 , 2:작업자,관리자 , 3:관리자',
  PRIMARY KEY (`BOARD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='게시판';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_BOARD`
--

LOCK TABLES `PLM_BOARD` WRITE;
/*!40000 ALTER TABLE `PLM_BOARD` DISABLE KEYS */;
INSERT INTO `PLM_BOARD` VALUES ('BD_1',NULL,'공지사항','admin','2018-08-21 16:29:50',NULL,'admin','2018-09-18 14:16:18',NULL,'CT_4,',1,NULL,3,3),('BD_2',NULL,'test','admin','2018-11-09 15:19:10',NULL,'admin','2018-11-09 15:19:10',NULL,'CT_6,',3,NULL,3,3);
/*!40000 ALTER TABLE `PLM_BOARD` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_BOARD_CONTENTS`
--

DROP TABLE IF EXISTS `PLM_BOARD_CONTENTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_BOARD_CONTENTS` (
  `CONTS_ID` varchar(255) NOT NULL COMMENT '게시판ID',
  `PARENT_ID` varchar(255) DEFAULT NULL,
  `CONTS_TITLE` varchar(255) DEFAULT NULL COMMENT '제목',
  `CONTS_CONT` longtext DEFAULT NULL COMMENT '내용',
  `CONTS_DEL_YN` varchar(10) DEFAULT NULL COMMENT '삭제구분',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`CONTS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='게시글';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_BOARD_CONTENTS`
--

LOCK TABLES `PLM_BOARD_CONTENTS` WRITE;
/*!40000 ALTER TABLE `PLM_BOARD_CONTENTS` DISABLE KEYS */;
/*INSERT INTO `PLM_BOARD_CONTENTS` VALUES ('CT_1','BD_1','공지사항','<p>12</p>',NULL,'admin','2018-11-02 14:06:28',NULL,'admin','2018-12-13 17:14:36',NULL),('CT_3','BD_1','test','<p>test</p>',NULL,'admin','2018-12-13 14:55:16',NULL,'admin','2018-12-13 17:14:15',NULL),('CT_4','BD_1','notice','<p>asdfsadfasdfasdf</p>',NULL,'admin','2018-12-13 16:35:11',NULL,'admin','2018-12-13 17:15:04',NULL),('CT_5','BD_1','asdfasdfasf','<p>asdfasdfasdfadfasdfasdf</p>',NULL,'admin','2018-12-13 16:35:26',NULL,'admin','2018-12-13 17:14:45',NULL),('CT_6','BD_2','test','<p>testasfdasdfasdf</p>',NULL,'admin','2018-12-13 17:15:21',NULL,'admin','2018-12-13 17:15:33',NULL);*/
/*!40000 ALTER TABLE `PLM_BOARD_CONTENTS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_BOM_CATE`
--

DROP TABLE IF EXISTS `PLM_BOM_CATE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_BOM_CATE` (
  `BC_ID` varchar(255) NOT NULL COMMENT '카테고리ID',
  `BC_NM` varchar(255) NOT NULL COMMENT '카테고리명',
  `BC_CONT` longtext DEFAULT NULL COMMENT '내용',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`BC_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='부품카테고리관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_BOM_CATE`
--

LOCK TABLES `PLM_BOM_CATE` WRITE;
/*!40000 ALTER TABLE `PLM_BOM_CATE` DISABLE KEYS */;
/*INSERT INTO `PLM_BOM_CATE` VALUES ('CATE_1','카테1','<p>1</p>','admin','2018-11-02 14:08:37','admin','2018-12-07 12:52:48');*/
/*!40000 ALTER TABLE `PLM_BOM_CATE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_BOM_CATE_DTL`
--

DROP TABLE IF EXISTS `PLM_BOM_CATE_DTL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_BOM_CATE_DTL` (
  `BCD_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '상세ID',
  `BCD_AMT` int(11) DEFAULT 0,
  `BC_ID` varchar(255) NOT NULL COMMENT '카테고리ID',
  `BP_ID` varchar(255) NOT NULL COMMENT '부품ID',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`BCD_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='부품카테고리 상세';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_BOM_CATE_DTL`
--

LOCK TABLES `PLM_BOM_CATE_DTL` WRITE;
/*!40000 ALTER TABLE `PLM_BOM_CATE_DTL` DISABLE KEYS */;
/*INSERT INTO `PLM_BOM_CATE_DTL` VALUES (7,1,'CATE_1','PART_2','admin','2018-12-07 12:52:48','admin','2018-12-07 12:52:48'),(8,1,'CATE_1','PART_1','admin','2018-12-07 12:52:48','admin','2018-12-07 12:52:48');*/
/*!40000 ALTER TABLE `PLM_BOM_CATE_DTL` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_BOM_PART`
--

DROP TABLE IF EXISTS `PLM_BOM_PART`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_BOM_PART` (
  `BP_ID` varchar(255) NOT NULL COMMENT '부품ID',
  `BP_NM` varchar(255) NOT NULL COMMENT '부품명',
  `BP_STD` varchar(255) DEFAULT NULL COMMENT '규격',
  `BP_MTR` varchar(255) DEFAULT NULL COMMENT '재질',
  `BP_ASC` varchar(255) DEFAULT NULL,
  `BP_CONT` longtext DEFAULT NULL COMMENT '내용',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`BP_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='부품정보관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_BOM_PART`
--

LOCK TABLES `PLM_BOM_PART` WRITE;
/*!40000 ALTER TABLE `PLM_BOM_PART` DISABLE KEYS */;
/*INSERT INTO `PLM_BOM_PART` VALUES ('PART_1','부품1','100','철','휴비즈','<p>상세</p>','admin','2018-11-02 14:07:29','admin','2018-12-10 17:07:22'),('PART_2','부품2','300','아연','휴비즈ICT','<p>test</p>','admin','2018-12-07 12:51:30','admin','2018-12-10 17:10:41');*/
/*!40000 ALTER TABLE `PLM_BOM_PART` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_BOM_PDT`
--

DROP TABLE IF EXISTS `PLM_BOM_PDT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_BOM_PDT` (
  `BPD_ID` varchar(255) NOT NULL COMMENT '제품ID',
  `BPD_CD` varchar(255) DEFAULT NULL COMMENT '제품코드',
  `BPD_NM` varchar(255) NOT NULL COMMENT '제품명',
  `BPD_CONT` longtext DEFAULT NULL COMMENT '내용',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`BPD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='제품정보관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_BOM_PDT`
--

LOCK TABLES `PLM_BOM_PDT` WRITE;
/*!40000 ALTER TABLE `PLM_BOM_PDT` DISABLE KEYS */;
/*INSERT INTO `PLM_BOM_PDT` VALUES ('PDT_1','10000','제품1','상세내용','admin','2018-11-02 14:09:43','admin','2018-12-10 12:56:11');*/
/*!40000 ALTER TABLE `PLM_BOM_PDT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_BOM_PDT_CATE`
--

DROP TABLE IF EXISTS `PLM_BOM_PDT_CATE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_BOM_PDT_CATE` (
  `BPDD_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `BPD_ID` varchar(255) NOT NULL COMMENT '제품ID',
  `BC_ID` varchar(255) NOT NULL COMMENT '카테고리ID',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`BPDD_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='제품 연결 카테고리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_BOM_PDT_CATE`
--

LOCK TABLES `PLM_BOM_PDT_CATE` WRITE;
/*!40000 ALTER TABLE `PLM_BOM_PDT_CATE` DISABLE KEYS */;
/*INSERT INTO `PLM_BOM_PDT_CATE` VALUES (20,'PDT_1','CATE_1','admin','2018-12-10 12:56:11','admin','2018-12-10 12:56:11');*/
/*!40000 ALTER TABLE `PLM_BOM_PDT_CATE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_BOM_PDT_CNT`
--

DROP TABLE IF EXISTS `PLM_BOM_PDT_CNT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_BOM_PDT_CNT` (
  `BPA_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `BPD_ID` varchar(255) DEFAULT NULL COMMENT '제품ID',
  `BPA_GBN` varchar(10) DEFAULT NULL COMMENT '구분(부품:part,카테고리:cate)',
  `BPA_GBN_ID` varchar(255) DEFAULT NULL COMMENT '구분ID(부품ID,카테고리DTL ID)',
  `BPA_CNT` int(11) DEFAULT NULL COMMENT '수량',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`BPA_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 COMMENT='제품정보 수량관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_BOM_PDT_CNT`
--

LOCK TABLES `PLM_BOM_PDT_CNT` WRITE;
/*!40000 ALTER TABLE `PLM_BOM_PDT_CNT` DISABLE KEYS */;
/*INSERT INTO `PLM_BOM_PDT_CNT` VALUES (60,'PDT_1','part','PART_1',20,'admin','2018-12-10 12:56:11','admin','2018-12-10 12:56:11'),(61,'PDT_1','CATE_1','PART_2',1,'admin','2018-12-10 12:56:11','admin','2018-12-10 12:56:11'),(62,'PDT_1','CATE_1','PART_1',1,'admin','2018-12-10 12:56:11','admin','2018-12-10 12:56:11'),(63,'PP_1','part','PART_1',50,'admin','2018-12-12 09:09:14','admin','2018-12-12 09:09:14'),(64,'PP_1','part','PART_2',10,'admin','2018-12-12 09:09:14','admin','2018-12-12 09:09:14');*/
/*!40000 ALTER TABLE `PLM_BOM_PDT_CNT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_BOM_PDT_PART`
--

DROP TABLE IF EXISTS `PLM_BOM_PDT_PART`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_BOM_PDT_PART` (
  `BPDD_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `BPD_ID` varchar(255) NOT NULL COMMENT '제품ID',
  `BPD_AMT` int(11) NOT NULL COMMENT '부품개수',
  `BP_ID` varchar(255) NOT NULL COMMENT '부품ID',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`BPDD_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='제품 연결 부품';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_BOM_PDT_PART`
--

LOCK TABLES `PLM_BOM_PDT_PART` WRITE;
/*!40000 ALTER TABLE `PLM_BOM_PDT_PART` DISABLE KEYS */;
/*INSERT INTO `PLM_BOM_PDT_PART` VALUES (22,'PDT_1',0,'PART_1','admin','2018-12-10 12:56:11','admin','2018-12-10 12:56:11');*/
/*!40000 ALTER TABLE `PLM_BOM_PDT_PART` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_BOM_PMS`
--

DROP TABLE IF EXISTS `PLM_BOM_PMS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_BOM_PMS` (
  `BPMS_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '프로젝트매핑ID',
  `PP_ID` varchar(255) NOT NULL COMMENT '프로젝트ID',
  `BPMS_GBN` varchar(10) NOT NULL COMMENT '구분(부품 : PART , 카테고리 : CATE , 제품 : PDT)',
  `BPMS_GBN_ID` varchar(255) NOT NULL COMMENT '구분ID(부품, 카테고리, 제품)',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`BPMS_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='프로젝트매핑';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_BOM_PMS`
--

LOCK TABLES `PLM_BOM_PMS` WRITE;
/*!40000 ALTER TABLE `PLM_BOM_PMS` DISABLE KEYS */;
/*INSERT INTO `PLM_BOM_PMS` VALUES (37,'PP_1','PART','PART_1','admin','2018-12-12 09:09:14','admin','2018-12-12 09:09:14'),(38,'PP_1','PART','PART_2','admin','2018-12-12 09:09:14','admin','2018-12-12 09:09:14');*/
/*!40000 ALTER TABLE `PLM_BOM_PMS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_COMP`
--

DROP TABLE IF EXISTS `PLM_COMP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_COMP` (
  `PC_ID` varchar(255) NOT NULL COMMENT '거래처코드(COMP_)',
  `PC_NM` varchar(255) DEFAULT NULL COMMENT '거래처명',
  `PC_NUMBER` varchar(255) DEFAULT NULL COMMENT '사업자번호',
  `PC_EMP_NM` varchar(255) DEFAULT NULL COMMENT '대표자명',
  `PC_TEL` varchar(255) DEFAULT NULL COMMENT '연락처',
  `PC_DEL_YN` varchar(50) DEFAULT NULL COMMENT '삭제구분',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`PC_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='거래처관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_COMP`
--

LOCK TABLES `PLM_COMP` WRITE;
/*!40000 ALTER TABLE `PLM_COMP` DISABLE KEYS */;
/*INSERT INTO `PLM_COMP` VALUES ('COMP_1','거래처 1','1111','김거래','010',NULL,'admin','2018-11-02 13:55:14','admin','2018-11-02 13:55:14');*/
/*!40000 ALTER TABLE `PLM_COMP` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_COMP_LIST`
--

DROP TABLE IF EXISTS `PLM_COMP_LIST`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_COMP_LIST` (
  `COMPLIST_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '거래처리스트ID',
  `PLM_TYPE` varchar(50) NOT NULL COMMENT 'PLM유형(pdm,rm,...)',
  `PARENT_ID` varchar(255) NOT NULL COMMENT '게시글ID',
  `PC_ID` varchar(255) NOT NULL COMMENT '거래처ID',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`COMPLIST_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='담당자리스트';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_COMP_LIST`
--

LOCK TABLES `PLM_COMP_LIST` WRITE;
/*!40000 ALTER TABLE `PLM_COMP_LIST` DISABLE KEYS */;
/*INSERT INTO `PLM_COMP_LIST` VALUES (58,'pms','PP_1','COMP_1','admin','2018-11-02 14:04:33','admin','2018-11-02 14:04:33');*/
/*!40000 ALTER TABLE `PLM_COMP_LIST` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_EMP`
--

DROP TABLE IF EXISTS `PLM_EMP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_EMP` (
  `PE_ID` varchar(255) NOT NULL,
  `PE_NM` varchar(255) NOT NULL,
  `PE_PWD` varchar(255) NOT NULL,
  `PE_TEL` varchar(255) DEFAULT NULL COMMENT '연락처',
  `PE_AUTH` varchar(255) DEFAULT NULL COMMENT '권한(시스템관리자:admin,작업자:emp,사용자:user)',
  `PE_DEL_YN` varchar(50) DEFAULT NULL,
  `INS_ID` varchar(255) DEFAULT NULL,
  `INS_DT` datetime DEFAULT NULL,
  `UPD_ID` varchar(255) DEFAULT NULL,
  `UPD_DT` datetime DEFAULT NULL,
  `DEL_ID` varchar(255) DEFAULT NULL,
  `DEL_DT` datetime DEFAULT NULL,
  `ETC1` varchar(255) DEFAULT NULL COMMENT 'skin',
  `ETC2` varchar(255) DEFAULT NULL COMMENT '직급',
  PRIMARY KEY (`PE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='유저';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_EMP`
--

LOCK TABLES `PLM_EMP` WRITE;
/*!40000 ALTER TABLE `PLM_EMP` DISABLE KEYS */;
INSERT INTO `PLM_EMP` VALUES ('admin','관리자','admin1234','010-3424-1404','admin',NULL,NULL,NULL,'admin','2018-10-08 14:00:46',NULL,NULL,'Dark','사장1')/*,('job','작업자1','1234','010-2222-4544','emp',NULL,'admin','2018-11-02 09:39:38','admin','2018-12-05 09:07:15',NULL,NULL,NULL,'부장'),('test','test','1234','1234','user',NULL,'admin','2018-12-13 11:40:59','admin','2018-12-13 11:40:59',NULL,NULL,NULL,''),('test1','테스트','1234','010-1111-1111','emp',NULL,'admin','2018-11-02 13:53:09','admin','2018-12-11 08:50:24',NULL,NULL,NULL,'사원'),('test2','test2','1234','01065743156','emp',NULL,'admin','2018-12-10 17:46:10','admin','2018-12-11 08:50:31',NULL,NULL,NULL,'test'),('user','user','user','010-9878-0000','emp',NULL,'admin','2018-10-10 15:51:39','admin','2018-12-11 08:50:14',NULL,NULL,NULL,'')*/;
/*!40000 ALTER TABLE `PLM_EMP` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_EMP_LIST`
--

DROP TABLE IF EXISTS `PLM_EMP_LIST`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_EMP_LIST` (
  `EMPLIST_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '파일_담당자ID',
  `PLM_TYPE` varchar(50) NOT NULL COMMENT 'PLM유형(pdm,rm,...)',
  `PARENT_ID` varchar(255) NOT NULL COMMENT '게시글ID',
  `EMP_ID` varchar(255) NOT NULL COMMENT '담당자ID',
  `EMP_NM` varchar(255) NOT NULL COMMENT '담당자명',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  `LOOK_YN` varchar(50) DEFAULT NULL COMMENT '확인여부',
  PRIMARY KEY (`EMPLIST_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=299 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='담당자리스트';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_EMP_LIST`
--

LOCK TABLES `PLM_EMP_LIST` WRITE;
/*!40000 ALTER TABLE `PLM_EMP_LIST` DISABLE KEYS */;
/*INSERT INTO `PLM_EMP_LIST` VALUES (296,'pms','PP_1','job','작업자1','admin','2018-11-02 14:04:33','192.168.24.117',NULL,NULL,NULL,NULL),(297,'pdm2','PF_2','job','작업자1','admin','2018-11-02 14:05:45','192.168.24.117',NULL,NULL,NULL,NULL),(298,'rm','PR_1','job','작업자1','admin','2018-11-02 14:05:45','192.168.24.117',NULL,NULL,NULL,NULL);*/
/*!40000 ALTER TABLE `PLM_EMP_LIST` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_FA`
--

DROP TABLE IF EXISTS `PLM_FA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_FA` (
  `PLM_TYPE` varchar(50) DEFAULT NULL COMMENT '종류',
  `FA_ID` varchar(50) DEFAULT NULL COMMENT 'ID',
  `FA_USER` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='즐겨찾기';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_FA`
--

LOCK TABLES `PLM_FA` WRITE;
/*!40000 ALTER TABLE `PLM_FA` DISABLE KEYS */;
/*!40000 ALTER TABLE `PLM_FA` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_FILE_LIST`
--

DROP TABLE IF EXISTS `PLM_FILE_LIST`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_FILE_LIST` (
  `FILELIST_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '첨부파일ID',
  `PF_ID` varchar(255) DEFAULT NULL COMMENT '파일ID',
  `PARENT_ID` varchar(255) DEFAULT NULL COMMENT '글 ID',
  `PLM_TYPE` varchar(50) DEFAULT NULL,
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  `PLM_DETAIL_TYPE` varchar(255) DEFAULT NULL COMMENT '파일상세타입 : 댓글 reply : 노말 normal : 채팅 : cht',
  PRIMARY KEY (`FILELIST_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='첨부파일 리스트';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_FILE_LIST`
--

LOCK TABLES `PLM_FILE_LIST` WRITE;
/*!40000 ALTER TABLE `PLM_FILE_LIST` DISABLE KEYS */;
/*INSERT INTO `PLM_FILE_LIST` VALUES (126,'PF_2','PR_1','rm','admin','2018-11-02 14:05:45','192.168.24.117','admin','2018-11-02 14:05:45','192.168.24.117','normal'),(139,'PF_5','job','user','admin','2018-12-05 09:07:15','192.168.24.117','admin','2018-12-05 09:07:15','192.168.24.117','user'),(150,'PF_8','PDT_1','pdt','admin','2018-12-10 12:56:11','192.168.24.117','admin','2018-12-10 12:56:11','192.168.24.117','bom_pdt'),(151,'PF_9','PDT_1','pdt','admin','2018-12-10 12:56:11','192.168.24.117','admin','2018-12-10 12:56:11','192.168.24.117','bom_pdt'),(152,'PF_10','PART_1','part','admin','2018-12-10 12:57:55','192.168.24.117','admin','2018-12-10 12:57:55','192.168.24.117','bom_part'),(153,'PF_11','CT_1','board','admin','2018-12-10 12:58:28','192.168.24.117','admin','2018-12-10 12:58:28','192.168.24.117','board'),(155,'PF_12','PART_1','part','admin','2018-12-10 17:07:22','192.168.24.117','admin','2018-12-10 17:07:22','192.168.24.117','bom_part'),(156,'PF_13','PART_2','part','admin','2018-12-10 17:10:41','192.168.24.117','admin','2018-12-10 17:10:41','192.168.24.117','bom_part');*/
/*!40000 ALTER TABLE `PLM_FILE_LIST` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_FORMAT`
--

DROP TABLE IF EXISTS `PLM_FORMAT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_FORMAT` (
  `PF_ID` varchar(255) NOT NULL COMMENT '양식ID(FM_)',
  `PF_NM` varchar(255) DEFAULT NULL COMMENT '양식명',
  `PF_CONT` longtext DEFAULT NULL COMMENT '양식내용',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  PRIMARY KEY (`PF_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='양식관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_FORMAT`
--

LOCK TABLES `PLM_FORMAT` WRITE;
/*!40000 ALTER TABLE `PLM_FORMAT` DISABLE KEYS */;
/*INSERT INTO `PLM_FORMAT` VALUES ('FORMAT_1','ggg','<p>ggg</p>','2018-12-13 12:59:05','test2','2018-12-13 12:59:05','test2');*/
/*!40000 ALTER TABLE `PLM_FORMAT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_KEYWORD`
--

DROP TABLE IF EXISTS `PLM_KEYWORD`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_KEYWORD` (
  `PK_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '파일_키워드ID',
  `PLM_TYPE` varchar(50) NOT NULL COMMENT 'PLM TYPE',
  `PARENT_ID` varchar(255) NOT NULL COMMENT '부모ID',
  `PK_NM` varchar(255) DEFAULT NULL COMMENT '키워드명',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`PK_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='키워드';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_KEYWORD`
--

LOCK TABLES `PLM_KEYWORD` WRITE;
/*!40000 ALTER TABLE `PLM_KEYWORD` DISABLE KEYS */;
/*INSERT INTO `PLM_KEYWORD` VALUES (39,'pms','PP_1','12','admin','2018-11-02 14:04:33','192.168.24.117','admin','2018-11-02 14:04:33','192.168.24.117');*/
/*!40000 ALTER TABLE `PLM_KEYWORD` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_MAIL_CONFIG`
--

DROP TABLE IF EXISTS `PLM_MAIL_CONFIG`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_MAIL_CONFIG` (
  `MC_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '게시판ID',
  `MC_NM` varchar(255) NOT NULL,
  `EMP_ID` varchar(255) DEFAULT NULL,
  `MC_HOST` varchar(255) DEFAULT NULL COMMENT 'board이름',
  `MC_U_ID` varchar(255) DEFAULT NULL,
  `MC_U_PW` varchar(255) DEFAULT NULL,
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`MC_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='외부메일 관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_MAIL_CONFIG`
--

LOCK TABLES `PLM_MAIL_CONFIG` WRITE;
/*!40000 ALTER TABLE `PLM_MAIL_CONFIG` DISABLE KEYS */;
/*!40000 ALTER TABLE `PLM_MAIL_CONFIG` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_MSG`
--

DROP TABLE IF EXISTS `PLM_MSG`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_MSG` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `R_ID` varchar(255) NOT NULL COMMENT 'Receive id',
  `S_ID` varchar(255) NOT NULL COMMENT 'Send id',
  `MSG` longtext NOT NULL COMMENT '메세지내용',
  `INS_DT` datetime NOT NULL COMMENT '보낸날짜',
  `ETC1` varchar(255) DEFAULT NULL COMMENT 'etc...',
  `ETC2` varchar(255) DEFAULT NULL COMMENT '확인여부',
  `ETC3` varchar(255) DEFAULT NULL,
  `ETC4` varchar(255) DEFAULT NULL,
  `ETC5` varchar(255) DEFAULT NULL,
  `ETC6` varchar(255) DEFAULT NULL,
  `ETC7` varchar(255) DEFAULT NULL,
  `ETC8` varchar(255) DEFAULT NULL,
  `ETC9` varchar(255) DEFAULT NULL,
  `ETC10` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='메세지 보관함';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_MSG`
--

LOCK TABLES `PLM_MSG` WRITE;
/*!40000 ALTER TABLE `PLM_MSG` DISABLE KEYS */;
/*INSERT INTO `PLM_MSG` VALUES (27,'job','job','1234','2018-12-05 17:38:05','','','','','','','','','','');*/
/*!40000 ALTER TABLE `PLM_MSG` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_MSG_SEND`
--

DROP TABLE IF EXISTS `PLM_MSG_SEND`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_MSG_SEND` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `R_ID` varchar(255) NOT NULL COMMENT 'Receive id',
  `S_ID` varchar(255) NOT NULL COMMENT 'Send id',
  `MSG` longtext NOT NULL COMMENT '메세지내용',
  `INS_DT` datetime NOT NULL COMMENT '보낸날짜',
  `ETC1` varchar(255) DEFAULT NULL COMMENT 'etc...',
  `ETC2` varchar(255) DEFAULT NULL COMMENT '확인여부',
  `ETC3` varchar(255) DEFAULT NULL,
  `ETC4` varchar(255) DEFAULT NULL,
  `ETC5` varchar(255) DEFAULT NULL,
  `ETC6` varchar(255) DEFAULT NULL,
  `ETC7` varchar(255) DEFAULT NULL,
  `ETC8` varchar(255) DEFAULT NULL,
  `ETC9` varchar(255) DEFAULT NULL,
  `ETC10` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='메세지 보관함';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_MSG_SEND`
--

LOCK TABLES `PLM_MSG_SEND` WRITE;
/*!40000 ALTER TABLE `PLM_MSG_SEND` DISABLE KEYS */;
/*INSERT INTO `PLM_MSG_SEND` VALUES (25,'job','job','1234','2018-12-05 17:38:05','','','','','','','','','','');*/
/*!40000 ALTER TABLE `PLM_MSG_SEND` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_ORG`
--

DROP TABLE IF EXISTS `PLM_ORG`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_ORG` (
  `ORG_ID` varchar(50) NOT NULL COMMENT '조직도아이디',
  `ORG_NM` varchar(255) DEFAULT NULL COMMENT '조직도명',
  `ORG_DATA` longtext DEFAULT NULL COMMENT '조직도데이터(json)',
  `ORG_YN` varchar(50) DEFAULT NULL COMMENT '사용유무',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='조직도관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_ORG`
--

LOCK TABLES `PLM_ORG` WRITE;
/*!40000 ALTER TABLE `PLM_ORG` DISABLE KEYS */;
/*INSERT INTO `PLM_ORG` VALUES ('ORG_12','휴비즈ICT 조직도','{\n  \"name\": \"대표이사^심희택^010-0000-0000^^#ffc000\",\n  \"children\": [\n    {\n      \"name\": \"-^IT사업부^054-000-0000^^#ffc000\",\n      \"children\": [\n        {\n          \"name\": \"-^SM사업부^054-000-0000^^#938953\",\n          \"children\": [\n            {\n              \"name\": \"-^서울팀^054-000-0000^^#bfbfbf\",\n              \"children\": [\n                {\n                  \"name\": \"팀장^조희도^010-0000-0000^^#b8cce4\",\n                  \"children\": [\n                    {\n                      \"name\": \"팀장^유재석^010-0000-0000^^#b8cce4\",\n                      \"children\": [\n                        {\n                          \"name\": \"부장^김신석^010-0000-0000^^#b8cce4\",\n                          \"children\": [\n                            {\n                              \"name\": \"부장^박재현^010-0000-0000^^#b8cce4\",\n                              \"children\": [\n                                {\n                                  \"name\": \"부장^이현석^010-0000-0000^^#b8cce4\",\n                                  \"children\": [\n                                    {\n                                      \"name\": \"대리^김성재^010-0000-0000^^#b8cce4\"\n                                    }\n                                  ]\n                                }\n                              ]\n                            }\n                          ]\n                        }\n                      ]\n                    }\n                  ]\n                }\n              ]\n            },\n            {\n              \"name\": \"-^포항팀^054-000-0000^^#bfbfbf\",\n              \"children\": [\n                {\n                  \"name\": \"팀장^최위석^010-0000-0000^^#b8cce4\",\n                  \"children\": [\n                    {\n                      \"name\": \"팀장^임경환^010-0000-0000^^#b8cce4\",\n                      \"children\": [\n                        {\n                          \"name\": \"차장^신학수^010-0000-0000^^#b8cce4\",\n                          \"children\": [\n                            {\n                              \"name\": \"차장^손삼호^010-0000-0000^^#b8cce4\",\n                              \"children\": [\n                                {\n                                  \"name\": \"차장^최현정^010-0000-0000^^#b8cce4\",\n                                  \"children\": [\n                                    {\n                                      \"name\": \"과장^김진욱^010-0000-0000^^#b8cce4\",\n                                      \"children\": [\n                                        {\n                                          \"name\": \"과장^이은진^010-0000-0000^^#b8cce4\",\n                                          \"children\": [\n                                            {\n                                              \"name\": \"대리^박정훈^010-0000-0000^^#b8cce4\",\n                                              \"children\": [\n                                                {\n                                                  \"name\": \"대리^이상민^010-0000-0000^^#b8cce4\",\n                                                  \"children\": [\n                                                    {\n                                                      \"name\": \"대리^황미진^010-0000-0000^^#b8cce4\",\n                                                      \"children\": [\n                                                        {\n                                                          \"name\": \"사원^반현정^010-0000-0000^^#b8cce4\"\n                                                        }\n                                                      ]\n                                                    }\n                                                  ]\n                                                }\n                                              ]\n                                            }\n                                          ]\n                                        }\n                                      ]\n                                    }\n                                  ]\n                                }\n                              ]\n                            }\n                          ]\n                        }\n                      ]\n                    }\n                  ]\n                }\n              ]\n            },\n            {\n              \"name\": \"-^광양팀^054-000-0000^^#bfbfbf\",\n              \"children\": [\n                {\n                  \"name\": \"팀장^서대휘^010-0000-0000^^#b8cce4\",\n                  \"children\": [\n                    {\n                      \"name\": \"부장^김갑성^010-0000-0000^^#b8cce4\",\n                      \"children\": [\n                        {\n                          \"name\": \"부장^작업자1^010-2222-4544^job^#06ba9a\"\n                        }\n                      ]\n                    }\n                  ]\n                }\n              ]\n            }\n          ]\n        },\n        {\n          \"name\": \"-^SI사업부^054-000-0000^^#938953\",\n          \"children\": [\n            {\n              \"name\": \"본부장^석창화^010-0000-0000^codezzang^#b8cce4\",\n              \"children\": [\n                {\n                  \"name\": \"부장^김병기^010-0000-0000^^#b8cce4\",\n                  \"children\": [\n                    {\n                      \"name\": \"과장^송준호^010-0000-0000^^#b8cce4\",\n                      \"children\": [\n                        {\n                          \"name\": \"사원^박하늘^010-0000-0000^^#b8cce4\"\n                        }\n                      ]\n                    }\n                  ]\n                }\n              ]\n            }\n          ]\n        }\n      ]\n    },\n    {\n      \"name\": \"-^대외사업본부^054-000-0000^^#ffc000\",\n      \"children\": [\n        {\n          \"name\": \"-^대외SI사업부^054-000-0000^^#938953\",\n          \"children\": [\n            {\n              \"name\": \"팀장^김경식^010-0000-0000^ryan^#b8cce4\",\n              \"children\": [\n                {\n                  \"name\": \"과장^염정웅^010-0000-0000^yjw1404^#b8cce4\",\n                  \"children\": [\n                    {\n                      \"name\": \"사원^김다빈^010-0000-0000^dabin^#b8cce4\",\n                      \"children\": [\n                        {\n                          \"name\": \"사원^조민희^010-0000-0000^mini^#b8cce4\",\n                          \"children\": [\n                            {\n                              \"name\": \"부장^작업자1^010-2222-4544^job^#06ba9a\"\n                            }\n                          ]\n                        }\n                      ]\n                    }\n                  ]\n                }\n              ]\n            }\n          ]\n        }\n      ]\n    },\n    {\n      \"name\": \"-^기업부설연구소^054-000-0000^^#ffc000\",\n      \"children\": [\n        {\n          \"name\": \"-^신사업기획실^054-000-0000^^#938953\",\n          \"children\": [\n            {\n              \"name\": \"연구소장^최민호^010-0000-0000^^#b8cce4\",\n              \"children\": [\n                {\n                  \"name\": \"책임연구원^박해욱^010-0000-0000^^#b8cce4\",\n                  \"children\": [\n                    {\n                      \"name\": \"선임연구원^박은성^010-0000-0000^^#b8cce4\",\n                      \"children\": [\n                        {\n                          \"name\": \"선임연구원^심유정^010-0000-0000^^#b8cce4\"\n                        }\n                      ]\n                    }\n                  ]\n                }\n              ]\n            }\n          ]\n        },\n        {\n          \"name\": \"-^솔루션개발부^054-000-0000^^#938953\",\n          \"children\": [\n            {\n              \"name\": \"-^아키텍쳐연구개발팀^054-000-0000^^#bfbfbf\",\n              \"children\": [\n                {\n                  \"name\": \"부서장^이인철^010-0000-0000^^#b8cce4\",\n                  \"children\": [\n                    {\n                      \"name\": \"선임연구원^권효성^010-0000-0000^^#b8cce4\",\n                      \"children\": [\n                        {\n                          \"name\": \"연구원^송효섭^010-0000-0000^^#b8cce4\",\n                          \"children\": [\n                            {\n                              \"name\": \"연구원^유동금^010-0000-0000^^#b8cce4\",\n                              \"children\": [\n                                {\n                                  \"name\": \"연구원^김현호^010-0000-0000^^#b8cce4\",\n                                  \"children\": [\n                                    {\n                                      \"name\": \"연구원^백소연^010-0000-0000^^#b8cce4\"\n                                    }\n                                  ]\n                                }\n                              ]\n                            }\n                          ]\n                        }\n                      ]\n                    }\n                  ]\n                }\n              ]\n            },\n            {\n              \"name\": \"-^3D모델링팀^054-000-0000^^#bfbfbf\",\n              \"children\": [\n                {\n                  \"name\": \"선임연구원^김예성^010-0000-0000^^#b8cce4\",\n                  \"children\": [\n                    {\n                      \"name\": \"연구원^최경원^010-0000-0000^^#b8cce4\",\n                      \"children\": [\n                        {\n                          \"name\": \"연구원^사공경^010-0000-0000^^#b8cce4\",\n                          \"children\": [\n                            {\n                              \"name\": \"연구원^이채은^010-0000-0000^^#b8cce4\"\n                            }\n                          ]\n                        }\n                      ]\n                    }\n                  ]\n                }\n              ]\n            }\n          ]\n        }\n      ]\n    },\n    {\n      \"name\": \"-^공공사업본부^054-000-0000^^#ffc000\",\n      \"children\": [\n        {\n          \"name\": \"-^공공개발부^054-000-0000^^#938953\",\n          \"children\": [\n            {\n              \"name\": \"본부장^김신영^010-0000-0000^^#b8cce4\",\n              \"children\": [\n                {\n                  \"name\": \"팀장^임종호^010-0000-0000^^#b8cce4\",\n                  \"children\": [\n                    {\n                      \"name\": \"과장^허준녕^010-0000-0000^^#b8cce4\",\n                      \"children\": [\n                        {\n                          \"name\": \"대리^이수영^010-0000-0000^^#b8cce4\",\n                          \"children\": [\n                            {\n                              \"name\": \"대리^송영호^010-0000-0000^^#b8cce4\",\n                              \"children\": [\n                                {\n                                  \"name\": \"대리^최성환^010-0000-0000^^#b8cce4\",\n                                  \"children\": [\n                                    {\n                                      \"name\": \"사원^김기윤^010-0000-0000^^#b8cce4\",\n                                      \"children\": [\n                                        {\n                                          \"name\": \"사원^백승훈^010-0000-0000^^#b8cce4\",\n                                          \"children\": [\n                                            {\n                                              \"name\": \"사원^이순규^010-0000-0000^^#b8cce4\",\n                                              \"children\": [\n                                                {\n                                                  \"name\": \"사원^전성욱^010-0000-0000^^#b8cce4\",\n                                                  \"children\": [\n                                                    {\n                                                      \"name\": \"사원^최은민^010-0000-0000^^#b8cce4\"\n                                                    }\n                                                  ]\n                                                }\n                                              ]\n                                            }\n                                          ]\n                                        }\n                                      ]\n                                    }\n                                  ]\n                                }\n                              ]\n                            }\n                          ]\n                        }\n                      ]\n                    }\n                  ]\n                }\n              ]\n            }\n          ]\n        },\n        {\n          \"name\": \"-^기획영업부^054-000-0000^^#938953\",\n          \"children\": [\n            {\n              \"name\": \"과장^신배승^010-0000-0000^^#b8cce4\",\n              \"children\": [\n                {\n                  \"name\": \"대리^김미정^010-0000-0000^^#b8cce4\",\n                  \"children\": [\n                    {\n                      \"name\": \"사원^백단비^010-0000-0000^^#b8cce4\"\n                    }\n                  ]\n                }\n              ]\n            }\n          ]\n        }\n      ]\n    },\n    {\n      \"name\": \"-^경영지원본부^054-000-0000^^#ffc000\",\n      \"children\": [\n        {\n          \"name\": \"본부장^김영준^010-0000-0000^^#b8cce4\"\n        }\n      ]\n    },\n    {\n      \"name\": \"-^총괄이사(전무)^054-000-0000^^#b8cce4\"\n    }\n  ]\n}','N','2018-08-30 10:00:04','admin','2018-12-10 17:44:47','admin'),('ORG_13','테스트 조직도','{\n  \"name\": \"사장^김사장^1111^^#06ba9a\",\n  \"children\": [\n    {\n      \"name\": \"부사장^김사장^1111^^#06ba9a\",\n      \"children\": [\n        {\n          \"name\": \"test^test2^01065743156^test2^#06ba9a\"\n        }\n      ]\n    },\n    {\n      \"name\": \"부사장^김사장^1111^^#06ba9a\"\n    },\n    {\n      \"name\": \"부장^작업자1^010-2222-4544^job^#06ba9a\"\n    }\n  ]\n}','Y','2018-11-02 13:54:52','admin','2018-12-11 08:51:12','admin');*/
/*!40000 ALTER TABLE `PLM_ORG` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_PDM_EMP_VERSION`
--

DROP TABLE IF EXISTS `PLM_PDM_EMP_VERSION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_PDM_EMP_VERSION` (
  `PEV_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '파일_담당자ID',
  `PF_ID` varchar(255) NOT NULL COMMENT '파일ID',
  `PE_EMP_ID` varchar(255) NOT NULL COMMENT '담당자ID',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`PEV_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='파일_담당자 이력관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_PDM_EMP_VERSION`
--

LOCK TABLES `PLM_PDM_EMP_VERSION` WRITE;
/*!40000 ALTER TABLE `PLM_PDM_EMP_VERSION` DISABLE KEYS */;
/*!40000 ALTER TABLE `PLM_PDM_EMP_VERSION` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_PDM_FILE`
--

DROP TABLE IF EXISTS `PLM_PDM_FILE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_PDM_FILE` (
  `PF_ID` varchar(255) NOT NULL COMMENT '파일ID',
  `PFD_ID` varchar(255) NOT NULL COMMENT '부모폴더ID',
  `PF_NM` varchar(255) DEFAULT NULL COMMENT '파일이름',
  `PF_INIT_ID_TYPE` varchar(255) DEFAULT NULL COMMENT '최초 타입 (rm/pdm/..)',
  `PF_NOW_ID_TYPE` varchar(255) DEFAULT NULL COMMENT '현재 타입 (rm/pdm/..)',
  `PF_DEL_YN` varchar(10) DEFAULT NULL COMMENT '파일삭제유무',
  `PF_PATH` varchar(255) DEFAULT NULL COMMENT '파일경로',
  `PP_ID` varchar(255) DEFAULT NULL COMMENT '프로젝트ID',
  `PF_CONT` longtext DEFAULT NULL COMMENT '파일내용',
  `PF_FILE_REAL_NM` varchar(255) DEFAULT NULL COMMENT '첨부파일 원래파일명',
  `PF_FILE_TEMP_NM` varchar(255) DEFAULT NULL COMMENT '첨부파일 서버파일명',
  `PF_FILE_SIZE` varchar(255) DEFAULT NULL COMMENT '첨부파일 크기',
  `PF_FILE_EXT` varchar(255) DEFAULT NULL COMMENT '첨부파일 확장자',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`PF_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='파일';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_PDM_FILE`
--

LOCK TABLES `PLM_PDM_FILE` WRITE;
/*!40000 ALTER TABLE `PLM_PDM_FILE` DISABLE KEYS */;
/*INSERT INTO `PLM_PDM_FILE` VALUES ('PF_1','PLM','pnr구매관리 이클립스','pdm2','pdm2','Y','PDM','','<p>pnr구매관리 이클립스</p>','work (2).zip','tmpphpGmVMol.zip','843308286','zip','admin','2018-10-16 14:51:44','192.168.24.117','admin','2018-12-10 17:41:47','192.168.24.117'),('PF_10','','부품1(0)','part','part',NULL,'','','<p>상세</p>','회의록.hwp','tmpphp19LEzJ.hwp','24576','hwp','admin','2018-12-10 12:57:55','192.168.24.117','admin','2018-12-10 12:57:55','192.168.24.117'),('PF_11','','공지사항','board','board',NULL,'','','<p>12</p>','VOC처리계획.xls','tmpphpL9KtCp.xls','37888','xls','admin','2018-12-10 12:58:28','192.168.24.117','admin','2018-12-13 17:14:36','192.168.24.117'),('PF_12','','부품1(0)','part','part',NULL,'','','<p>상세</p>','Chrysanthemum.jpg','tmpphp44p0iO.jpg','879394','jpg','admin','2018-12-10 17:07:22','192.168.24.117','admin','2018-12-10 17:07:22','192.168.24.117'),('PF_13','','부품2(0)','part','part',NULL,'','','<p>test</p>','Wildlife.wmv','tmpphpjpkR3J.wmv','26246026','wmv','admin','2018-12-10 17:10:41','192.168.24.117','admin','2018-12-10 17:10:41','192.168.24.117'),('PF_14','PFD_1','hjhjhj','pdm2','pdm2','Y','PDM > test','','<p>k,jkjj</p>','E14 불량툴.elt','tmpphpUF8DlF.elt','13029519','elt','admin','2018-12-13 13:24:22','211.224.63.191','admin','2018-12-13 13:25:44','211.224.63.191'),('PF_2','','요구사항(0)','rm','rm',NULL,'','','<p>상세</p>','주간업무보고_180518_ 취합본.pptx','tmpphpbIlJOY.pptx','211268','pptx','admin','2018-11-02 14:05:45','192.168.24.117','admin','2018-11-02 14:05:45','192.168.24.117'),('PF_5','','작업자1(0)','user','user',NULL,'','','작업자1','Chrysanthemum.jpg','tmpphpAmXgUH.jpg','879394','jpg','admin','2018-12-05 09:07:15','192.168.24.117','admin','2018-12-05 09:07:15','192.168.24.117'),('PF_7','PFD_3','파일','pdm2','pdm2','Y','PDM>도면>2018년 11월 23일','','<p>test</p>','Desktop.zip','tmpphpwXyVJ9.zip','14475669','zip','admin','2018-12-10 12:45:38','192.168.24.117','admin','2018-12-10 13:44:48','192.168.24.117'),('PF_8','','제품1(0)','pdt','pdt',NULL,'','','상세내용','회의록.hwp','tmpphpzjM8r9.hwp','24576','hwp','admin','2018-12-10 12:56:11','192.168.24.117','admin','2018-12-10 12:56:11','192.168.24.117'),('PF_9','','제품1(1)','pdt','pdt',NULL,'','','상세내용','VOC처리계획.xls','tmpphpX8hwg1.xls','37888','xls','admin','2018-12-10 12:56:11','192.168.24.117','admin','2018-12-10 12:56:11','192.168.24.117');*/
/*!40000 ALTER TABLE `PLM_PDM_FILE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_PDM_FILE_VERSION`
--

DROP TABLE IF EXISTS `PLM_PDM_FILE_VERSION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_PDM_FILE_VERSION` (
  `PFV_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '파일버전ID',
  `PF_ID` varchar(255) NOT NULL COMMENT '파일ID',
  `PFD_ID` varchar(255) NOT NULL COMMENT '부모폴더ID',
  `PF_NM` varchar(255) DEFAULT NULL COMMENT '파일이름',
  `PF_PATH` varchar(255) DEFAULT NULL COMMENT '파일경로',
  `PP_ID` varchar(255) DEFAULT NULL COMMENT '프로젝트ID',
  `PF_CONT` longtext DEFAULT NULL COMMENT '파일내용',
  `PF_FILE_REAL_NM` varchar(255) DEFAULT NULL COMMENT '첨부파일 원래파일명',
  `PF_FILE_TEMP_NM` varchar(255) DEFAULT NULL COMMENT '첨부파일 서버파일명',
  `PF_FILE_SIZE` varchar(255) DEFAULT NULL COMMENT '첨부파일 크기',
  `PF_FILE_EXT` varchar(255) DEFAULT NULL COMMENT '첨부파일 확장자',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`PFV_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='파일이력관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_PDM_FILE_VERSION`
--

LOCK TABLES `PLM_PDM_FILE_VERSION` WRITE;
/*!40000 ALTER TABLE `PLM_PDM_FILE_VERSION` DISABLE KEYS */;
/*!40000 ALTER TABLE `PLM_PDM_FILE_VERSION` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_PDM_FOLDER`
--

DROP TABLE IF EXISTS `PLM_PDM_FOLDER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_PDM_FOLDER` (
  `PFD_ID` varchar(255) NOT NULL COMMENT '폴더ID',
  `PFD_PARENT_ID` varchar(255) DEFAULT NULL COMMENT '폴더부모ID',
  `PFD_NM` varchar(255) DEFAULT NULL COMMENT '폴더명',
  `PFD_DEL_YN` varchar(10) DEFAULT NULL COMMENT '삭제구분',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`PFD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='폴더구조';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_PDM_FOLDER`
--

LOCK TABLES `PLM_PDM_FOLDER` WRITE;
/*!40000 ALTER TABLE `PLM_PDM_FOLDER` DISABLE KEYS */;
/*INSERT INTO `PLM_PDM_FOLDER` VALUES ('PFD_1','PLM','test',NULL,'admin','2018-12-11 08:59:34','192.168.24.117',NULL,NULL,NULL),('PFD_2','PLM','새폴더',NULL,'admin','2018-12-13 16:03:23','211.224.63.191',NULL,NULL,NULL),('PLM','#','PDM',NULL,'admin',NULL,'192.168.24.117',NULL,NULL,NULL);*/
/*!40000 ALTER TABLE `PLM_PDM_FOLDER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_PDM_KEYWORD`
--

DROP TABLE IF EXISTS `PLM_PDM_KEYWORD`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_PDM_KEYWORD` (
  `PK_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '파일_키워드ID',
  `PF_ID` varchar(255) NOT NULL COMMENT '파일ID',
  `PK_NM` varchar(255) DEFAULT NULL COMMENT '키워드명',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`PK_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=291 DEFAULT CHARSET=utf8 COMMENT='파일_키워드';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_PDM_KEYWORD`
--

LOCK TABLES `PLM_PDM_KEYWORD` WRITE;
/*!40000 ALTER TABLE `PLM_PDM_KEYWORD` DISABLE KEYS */;
/*INSERT INTO `PLM_PDM_KEYWORD` VALUES (252,'PF_1','pnr','admin','2018-10-16 14:51:44','192.168.24.117',NULL,NULL,NULL),(253,'PF_1','구매관리','admin','2018-10-16 14:51:44','192.168.24.117',NULL,NULL,NULL),(254,'PF_1','이클립스','admin','2018-10-16 14:51:44','192.168.24.117',NULL,NULL,NULL),(255,'PF_2','요구사항관리','admin','2018-11-02 14:05:45','192.168.24.117',NULL,NULL,NULL),(256,'PF_2','111','admin','2018-11-02 14:05:45','192.168.24.117',NULL,NULL,NULL),(269,'PF_5','유저관리','admin','2018-12-05 09:07:15','192.168.24.117',NULL,NULL,NULL),(270,'PF_5','작업자1','admin','2018-12-05 09:07:15','192.168.24.117',NULL,NULL,NULL),(281,'PF_7','','admin','2018-12-10 12:45:38','192.168.24.117',NULL,NULL,NULL),(282,'PF_8','제품정보','admin','2018-12-10 12:56:11','192.168.24.117',NULL,NULL,NULL),(283,'PF_9','제품정보','admin','2018-12-10 12:56:11','192.168.24.117',NULL,NULL,NULL),(284,'PF_10','부품정보','admin','2018-12-10 12:57:55','192.168.24.117',NULL,NULL,NULL),(285,'PF_11','게시판','admin','2018-12-10 12:58:28','192.168.24.117',NULL,NULL,NULL),(288,'PF_12','부품정보','admin','2018-12-10 17:07:22','192.168.24.117',NULL,NULL,NULL),(289,'PF_13','부품정보','admin','2018-12-10 17:10:41','192.168.24.117',NULL,NULL,NULL),(290,'PF_14','','admin','2018-12-13 13:24:22','211.224.63.191',NULL,NULL,NULL);*/
/*!40000 ALTER TABLE `PLM_PDM_KEYWORD` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_PDM_KEYWORD_VERSION`
--

DROP TABLE IF EXISTS `PLM_PDM_KEYWORD_VERSION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_PDM_KEYWORD_VERSION` (
  `PKV_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '파일_키워드ID',
  `PF_ID` varchar(255) NOT NULL COMMENT '파일ID',
  `PK_NM` varchar(255) DEFAULT NULL COMMENT '키워드명',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`PKV_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='파일_키워드';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_PDM_KEYWORD_VERSION`
--

LOCK TABLES `PLM_PDM_KEYWORD_VERSION` WRITE;
/*!40000 ALTER TABLE `PLM_PDM_KEYWORD_VERSION` DISABLE KEYS */;
/*!40000 ALTER TABLE `PLM_PDM_KEYWORD_VERSION` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_PMS`
--

DROP TABLE IF EXISTS `PLM_PMS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_PMS` (
  `PP_ID` varchar(255) NOT NULL COMMENT '프로젝트ID',
  `PP_NM` varchar(255) DEFAULT NULL COMMENT '프로젝트명',
  `PP_ST_DAT` datetime DEFAULT NULL COMMENT '시작일',
  `PP_ED_DAT` datetime DEFAULT NULL COMMENT '종료일',
  `PP_CONT` varchar(4000) DEFAULT NULL COMMENT '내용',
  `PP_STATUS` varchar(20) DEFAULT NULL COMMENT '조치상태',
  `PC_ID` varchar(255) DEFAULT NULL COMMENT '거래처ID',
  `PP_DEL_YN` varchar(10) DEFAULT NULL COMMENT '삭제구분',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`PP_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='프로젝트';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_PMS`
--

LOCK TABLES `PLM_PMS` WRITE;
/*!40000 ALTER TABLE `PLM_PMS` DISABLE KEYS */;
/*INSERT INTO `PLM_PMS` VALUES ('PP_1','111','0000-00-00 00:00:00','0000-00-00 00:00:00','<p>비고</p>','1',NULL,NULL,'admin','2018-11-02 14:04:33','192.168.24.117','admin','2018-11-02 14:04:33','192.168.24.117');*/
/*!40000 ALTER TABLE `PLM_PMS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_PMS_LIST`
--

DROP TABLE IF EXISTS `PLM_PMS_LIST`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_PMS_LIST` (
  `PMSLIST_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `PLM_TYPE` varchar(50) NOT NULL COMMENT 'PLM유형(pdm,rm,...)',
  `PARENT_ID` varchar(255) NOT NULL COMMENT '게시글ID',
  `PP_ID` varchar(255) NOT NULL COMMENT '담당자ID',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`PMSLIST_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='프로젝트리스트';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_PMS_LIST`
--

LOCK TABLES `PLM_PMS_LIST` WRITE;
/*!40000 ALTER TABLE `PLM_PMS_LIST` DISABLE KEYS */;
/*INSERT INTO `PLM_PMS_LIST` VALUES (168,'pdm2','PF_2','PP_1','admin','2018-11-02 14:05:45','192.168.24.117','admin','2018-11-02 14:05:45','192.168.24.117'),(169,'rm','PR_1','PP_1','admin','2018-11-02 14:05:45','192.168.24.117','admin','2018-11-02 14:05:45','192.168.24.117'),(170,'pdm2','PF_14','PP_1','admin','2018-12-13 13:24:22','211.224.63.191','admin','2018-12-13 13:24:22','211.224.63.191');*/
/*!40000 ALTER TABLE `PLM_PMS_LIST` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_PMS_WBS`
--

DROP TABLE IF EXISTS `PLM_PMS_WBS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_PMS_WBS` (
  `PPD_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '프로젝트상세업무ID',
  `PP_ID` varchar(255) DEFAULT NULL COMMENT '프로젝트ID',
  `EMP_LIST` longtext DEFAULT NULL,
  `CAN_ADD` varchar(50) DEFAULT NULL,
  `CAN_ADD_ISSUE` varchar(255) DEFAULT NULL,
  `CAN_DELETE` varchar(255) DEFAULT NULL,
  `CAN_WRITE` varchar(255) DEFAULT NULL,
  `CODE` varchar(255) DEFAULT NULL,
  `COLLAPSED` varchar(255) DEFAULT NULL,
  `DEPENDS` varchar(255) DEFAULT NULL,
  `DESCRIPTION` varchar(255) DEFAULT NULL,
  `DURATION` varchar(255) DEFAULT NULL,
  `END` varchar(50) DEFAULT NULL,
  `END_IS_MILESTONE` varchar(255) DEFAULT NULL,
  `HAS_CHILD` varchar(255) DEFAULT NULL,
  `ID` varchar(255) DEFAULT NULL,
  `LEVEL` varchar(255) DEFAULT NULL,
  `NAME` varchar(255) DEFAULT NULL,
  `PROGRESS` varchar(255) DEFAULT NULL,
  `PROGRESS_BY_WORKLOG` varchar(255) DEFAULT NULL,
  `RELEVANCE` varchar(255) DEFAULT NULL,
  `START` varchar(50) DEFAULT NULL,
  `START_IS_MILESTONE` varchar(255) DEFAULT NULL,
  `STATUS` varchar(255) DEFAULT NULL,
  `TYPE` varchar(255) DEFAULT NULL,
  `TYPE_ID` varchar(255) DEFAULT NULL,
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`PPD_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='프로젝트_상세일정';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_PMS_WBS`
--

LOCK TABLES `PLM_PMS_WBS` WRITE;
/*!40000 ALTER TABLE `PLM_PMS_WBS` DISABLE KEYS */;
/*INSERT INTO `PLM_PMS_WBS` VALUES (37,'PP_1',NULL,'true','true','true','true','','true','','','1','1541429999999','false',NULL,'tmp_fk1541135077164_1','0','프로젝트1','0','false','0','1541343600000','true','STATUS_ACTIVE',NULL,NULL,'admin','2018-11-02 14:04:33','192.168.24.117','admin','2018-11-02 14:04:33','192.168.24.117'),(38,'PP_1',NULL,'true','true','true','true','',NULL,'','','1','1541429999999','false',NULL,'tmp_fk1541135080664_1','1','소1','0','false','0','1541343600000','true','STATUS_ACTIVE',NULL,NULL,'admin','2018-11-02 14:04:33','192.168.24.117','admin','2018-11-02 14:04:33','192.168.24.117');*/
/*!40000 ALTER TABLE `PLM_PMS_WBS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_REPLY`
--

DROP TABLE IF EXISTS `PLM_REPLY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_REPLY` (
  `REPLY_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '답변ID',
  `PARENT_ID` varchar(255) DEFAULT NULL COMMENT '부모ID',
  `PLM_TYPE` varchar(255) DEFAULT NULL COMMENT 'PLM 타입',
  `REPLY_CONT` longtext DEFAULT NULL COMMENT '내용',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`REPLY_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='답글';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_REPLY`
--

LOCK TABLES `PLM_REPLY` WRITE;
/*!40000 ALTER TABLE `PLM_REPLY` DISABLE KEYS */;
/*!40000 ALTER TABLE `PLM_REPLY` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PLM_RM`
--

DROP TABLE IF EXISTS `PLM_RM`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLM_RM` (
  `PR_ID` varchar(255) NOT NULL COMMENT '요구사항ID',
  `PP_ID` varchar(255) DEFAULT NULL COMMENT '프로젝트ID',
  `PR_TITLE` varchar(255) DEFAULT NULL COMMENT '제목',
  `PR_HOPE_END_DAT` datetime DEFAULT NULL COMMENT '완료요청일',
  `PR_CONT` longtext DEFAULT NULL COMMENT '내용',
  `PR_STATUS` varchar(20) DEFAULT NULL COMMENT '조치상태( 1:접수완료 , 2:조치완료, 3:진행중, 4:반려 )',
  `PR_DEL_YN` varchar(10) DEFAULT NULL COMMENT '삭제구분',
  `INS_ID` varchar(255) DEFAULT NULL COMMENT '등록ID',
  `INS_DT` datetime DEFAULT NULL COMMENT '등록일',
  `INS_IP` varchar(255) DEFAULT NULL COMMENT '등록IP',
  `UPD_ID` varchar(255) DEFAULT NULL COMMENT '수정ID',
  `UPD_DT` datetime DEFAULT NULL COMMENT '수정일',
  `UPD_IP` varchar(255) DEFAULT NULL COMMENT '수정IP',
  PRIMARY KEY (`PR_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='요구사항관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PLM_RM`
--

LOCK TABLES `PLM_RM` WRITE;
/*!40000 ALTER TABLE `PLM_RM` DISABLE KEYS */;
/*INSERT INTO `PLM_RM` VALUES ('PR_1',NULL,'요구사항','2018-12-07 00:00:00','<p>상세</p>','1',NULL,'admin','2018-11-02 14:05:45','192.168.24.117','admin','2018-11-02 14:05:45','192.168.24.117');*/
/*!40000 ALTER TABLE `PLM_RM` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-12-13 17:26:52
