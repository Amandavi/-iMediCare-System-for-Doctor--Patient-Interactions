-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2021 at 07:30 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_imedicare`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_calculate_contract` (IN `suuid` VARCHAR(50))  BEGIN
select tbl_boq_product_details.product_id ,tbl_products.name
, sum(tbl_boq_product_details.qty *    tbl_temp_boq_details.catonsQty ) as total
,tbl_boq_product_details.qty
from tbl_boq
inner join tbl_boq_product_details on tbl_boq_product_details.boq_id = tbl_boq.boq_id
inner join tbl_temp_boq_details on tbl_temp_boq_details.boq_id = tbl_boq.boq_id
inner join tbl_products on tbl_products.product_id = tbl_boq_product_details.product_id
and uuid = suuid
group by tbl_boq_product_details.product_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_calculate_prn` (IN `suuid` VARCHAR(50))  BEGIN

select tbl_contract_boqs_details.product_id ,tbl_products.name
, sum(tbl_contract_boqs_details.totalQty) as total
from tbl_contract_boqs_details
inner join tbl_temp_contract_ids on tbl_temp_contract_ids.contract_id = tbl_contract_boqs_details.contract_id
inner join tbl_products on tbl_products.product_id = tbl_contract_boqs_details.product_id
and uuid = suuid
group by tbl_contract_boqs_details.product_id
;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_loged_details` (IN `user_id` INT)  BEGIN

select 
'login_user' as type,
tbl_users.user_Id as id,
tbl_users.full_name as profile_name,
tbl_users.image as image_path,
tbl_user_groups.user_group_name as user_group_name,
'' as logo 
from tbl_users
inner join tbl_user_groups on tbl_user_groups.user_group_Id = tbl_users.user_group_Id
where tbl_users.user_Id = user_id
union 
select 
'company' as type,
tbl_company.company_id as id,
tbl_company.company_name as profile_name,
'' as image_path ,
'company_admin' as user_group_name,
tbl_company.logo as logo 
from tbl_company


;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_other_rights` (IN `user_Id` INT, IN `user_right_code` VARCHAR(50))  BEGIN

select tbl_users.user_Id
from tbl_users
inner join tbl_user_group_rights on tbl_user_group_rights.user_group_Id = tbl_users.user_group_Id
inner join tbl_user_rights on tbl_user_rights.user_right_Id = tbl_user_group_rights.user_right_Id
where tbl_users.user_Id = user_Id
 and   length(trim(tbl_user_rights.user_right_code))>0
 and tbl_user_rights.user_right_code = user_right_code;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_production_plan` (IN `planId` INT)  BEGIN

  
DECLARE finished INTEGER DEFAULT 0;
DECLARE _boqId int DEFAULT 0; 
DECLARE _parent_id int DEFAULT 0; 
DECLARE _product_id int DEFAULT 0; 
DECLARE _is_proceed_count int DEFAULT 0; 
DEClARE curProduction CURSOR FOR select boqId,parent_id,product_id   from temp_boq where is_proceed = 1;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1; 
set @rowid:=0;

-- delete select plan data
delete from tbl_production_plans_process where plan_id =planId;
-- drop tmp table
DROP TEMPORARY TABLE IF EXISTS temp_boq;

-- create root main contract items to tmp
CREATE TEMPORARY TABLE temp_boq ( boqId int,is_proceed int,tree_id varchar(50),parent_id int,product_id int,qty decimal(11,2));
insert into temp_boq
select tbl_boq.boq_id,1,@rowid:=@rowid+1 as rowid ,0,0,tbl_contract_boqs.qty from tbl_boq 
inner join tbl_products on tbl_products.product_id = tbl_boq.product_id
inner join tbl_contract_boqs on tbl_contract_boqs.boq_id = tbl_boq.boq_id
inner join tbl_contracts on tbl_contracts.contract_id = tbl_contract_boqs.contract_id
inner join tbl_production_plans_contracts on tbl_production_plans_contracts.contract_id = tbl_contract_boqs.contract_id
inner join tbl_production_plans on tbl_production_plans.plan_id = tbl_production_plans_contracts.plan_id
and tbl_production_plans_contracts.plan_id = planId
and tbl_products.is_row_material = 0;

set @rowid:=0;

-- add root main contract items 
INSERT INTO  `tbl_production_plans_process`
(  `plan_id`,
`is_row_material`,
`product_id`,
`tree_id` ,
`qty`,
boqId,
parent_id)
select planId,
tbl_products.is_row_material,
tbl_products. product_id,
@rowid:=@rowid+1,
tbl_contract_boqs.qty,
tbl_boq.boq_id,
tbl_boq.boq_id
from tbl_boq 
inner join tbl_products on tbl_products.product_id = tbl_boq.product_id
inner join tbl_contract_boqs on tbl_contract_boqs.boq_id = tbl_boq.boq_id
inner join tbl_contracts on tbl_contracts.contract_id = tbl_contract_boqs.contract_id
inner join tbl_production_plans_contracts on tbl_production_plans_contracts.contract_id = tbl_contract_boqs.contract_id
inner join tbl_production_plans on tbl_production_plans.plan_id = tbl_production_plans_contracts.plan_id
and tbl_production_plans_contracts.plan_id = planId
and tbl_products.is_row_material = 0;

set @rowid:=0;
 
atop:LOOP	-- inner boq loop
set  finished =0;
select 	count(*) 
 into _is_proceed_count    
 from temp_boq 
 where is_proceed = 1; 
	if(_is_proceed_count = 0) then
		LEAVE atop; -- exit from all loops
	end if;
       
OPEN curProduction; 
	getEmail: LOOP
		FETCH curProduction INTO _boqId,_parent_id,_product_id;
		IF finished = 1 THEN  
			LEAVE getEmail;
		END IF;
        --     select  _boqId,_parent_id,_product_id;

         set @rowid:=0;
		 set @parant:=0;
         set @parant_qty:=0.0000000;
         select 	 tree_id,qty
			into @parant,@parant_qty    
		 from temp_boq 
		 where boqId = _boqId
         and parent_id = _parent_id;
         
          /*
          
          -- gwt inner boq product id
          set @row_product_id:=0;
          select  product_id into @row_product_id from tbl_boq where product_id in (
		 SELECT tbl_products.product_id FROM tbl_boq_product_details 
		 inner join tbl_products on tbl_products. product_id =tbl_boq_product_details.product_id
		 where boq_id = _boqId -- and boq_id not in (select boqId from temp_boq )
		 and is_row_material = 0);
         
         -- get product row id
         set @parant2:=0;
		 set @rowid_p:=0;
		 select row_id into @parant2  from (
		 select tbl_boq_product_details.*, @rowid_p:=@rowid_p+1  as row_id
		 from tbl_boq
		 inner join tbl_boq_product_details on tbl_boq_product_details.boq_id = tbl_boq.boq_id
		 where tbl_boq.boq_id = _boqId
		 order by tbl_boq_product_details.product_id) tmp
		 where product_id = @row_product_id;
                  
		 --  select current boq qty
		 set @selected_item_qty:=0;
		 SELECT tbl_boq_product_details.Qty into @selected_item_qty
         FROM tbl_boq_product_details 
		 inner join tbl_products on tbl_products. product_id =tbl_boq_product_details.product_id
		 where boq_id = _boqId -- and boq_id not in (select boqId from temp_boq )
		  and is_row_material = 0;
   
   */
    set @rowid_p:=0;
    DROP TEMPORARY TABLE IF EXISTS tmp_row_table;
      CREATE TEMPORARY TABLE IF NOT EXISTS tmp_row_table AS (
   select tbl_boq_product_details.product_id, @rowid_p:=@rowid_p+1  as row_id,tbl_boq_product_details.Qty
		 from tbl_boq
		 inner join tbl_boq_product_details on tbl_boq_product_details.boq_id = tbl_boq.boq_id
		 where tbl_boq.boq_id = _boqId
		 order by tbl_boq_product_details.product_id) ;
         
 -- select * from tmp_row_table;
	     -- add inner boq main item
        -- set @parant2:=0;
        
       
        
	   insert into temp_boq
		 select boq_id,1, concat(@parant,'-', tmp_row_table.row_id )as rowid,_boqId,tbl_boq.product_id,tmp_row_table.Qty*@parant_qty 
         from tbl_boq 
         left join tmp_row_table on tmp_row_table.product_id = tbl_boq.product_id
         where tbl_boq.product_id in (
		 SELECT tbl_products.product_id FROM tbl_boq_product_details 
		 inner join tbl_products on tbl_products. product_id =tbl_boq_product_details.product_id
		 where boq_id = _boqId -- and boq_id not in (select boqId from temp_boq )
		 and is_row_material = 0
         );
        
         -- update if boq products inserted to table
         update temp_boq set is_proceed = 0   where boqId = _boqId;
      
		 -- insert to data table
         INSERT INTO  `tbl_production_plans_process`
			(  `plan_id`,
			`is_row_material`,
			`product_id`,
			`tree_id` ,
			`qty`,
            boqId)
			select planId,
			tbl_products.is_row_material,
			tbl_products. product_id,
			concat(@parant,'-', @rowid:=@rowid+1 ) ,
			 tbl_boq_product_details.Qty*@parant_qty    ,
            _boqId
			FROM tbl_boq_product_details 
			inner join tbl_products on tbl_products. product_id =tbl_boq_product_details.product_id
			where boq_id = _boqId
            order by tbl_boq_product_details.product_id;

 
         
      -- select _boqId;
	END LOOP getEmail;
	CLOSE curProduction;
 -- select * from temp_boq; 

 END LOOP atop;
 -- select * from temp_boq; 
-- select 'done';
drop table temp_boq;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_usergroup_rights` (IN `usergroupId` INT)  NO SQL
SELECT    
        CASE WHEN IFNULL(tbl_user_group_rights.user_right_Id,0)  =0 then 0
else 1
        END as selected,

-- tbl_user_rights.user_right_Id,tbl_user_rights.user_right_name ,main_menu_name,main_menu_code,second_level
tbl_user_rights.user_right_Id,tbl_user_rights.user_right_name ,main_menu_name,main_menu_code,sub_menu_id,second_level
FROM tbl_user_rights
left join tbl_user_group_rights  on tbl_user_rights.user_right_Id = tbl_user_group_rights.user_right_Id
and tbl_user_group_rights.user_group_Id = usergroupId
order by menu_order , sub_menu_id ,  permission_order   asc$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_rights` (IN `user_Id` INT)  BEGIN

select tbl_users.user_Id,tbl_users.user_group_Id,tbl_user_group_rights.user_right_Id,tbl_user_rights.user_right_code,tbl_user_rights.right_type,
tbl_user_rights.main_menu_name,tbl_user_rights.main_menu_code,tbl_user_rights.user_right_name,tbl_user_rights.page_path,
tbl_user_rights.main_menu_id,tbl_user_rights.sub_menu_id,tbl_user_rights.main_menu_icon,tbl_user_rights.second_level
from tbl_users
inner join tbl_user_group_rights on tbl_user_group_rights.user_group_Id = tbl_users.user_group_Id
inner join tbl_user_rights on tbl_user_rights.user_right_Id = tbl_user_group_rights.user_right_Id
where tbl_users.user_Id = user_Id
 and   length(trim(tbl_user_rights.user_right_code))>0
 order by menu_order DESC,main_menu_id,sub_menu_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateInventory` (`p_TransactionType` VARCHAR(50), `p_TransactionID` INT, `p_secondID` INT, `p_User` VARCHAR(50))  BEGIN
declare not_found int default 0;
declare v_QuantityAvaliable decimal(18,4); 
declare v_QuantityInstock decimal(18,4); 
declare v_QuantityReserved decimal(18,4); 
declare v_QuantityAllocated decimal(18,4); 
declare v_QuantitybackOrder decimal(18,4); 
declare v_SaleLocationID int;
declare v_GRNLocationID int;

declare v_productID int;
declare v_LocationID int;
declare v_ContainerID int;
declare v_OnOrderQty decimal(18,8);
declare v_QtyInStock decimal(18,8);
declare v_QtyReserved decimal(18,8);
declare v_QtyAllocated decimal(18,8);
declare v_call_allocation_sp int;
DECLARE finished INTEGER DEFAULT 0;

DECLARE _cursor CURSOR FOR select ProductID,LocationID,ContainerID,OnOrderQty,QtyInStock,QtyReserved,qtyAllocated,call_allocation_sp from InventoryList;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1; 
 
drop temporary table if exists InventoryList;
create temporary table   InventoryList(ProductID int , LocationID int , ContainerID int , OnOrderQty decimal(18,4) , 
QtyInStock decimal(18,4) , QtyReserved decimal(18,4),qtyAllocated decimal(18,4),call_allocation_sp int);

if(p_TransactionType = 'PRN')
then
	
	insert into InventoryList
	select tmp.product_id as ProductID , ifnull(tmp.location_id,0) as LocationID , 0 as ContainerID, 
	0 as OnOrderQty ,0 as QtyInStock,IFNULL(tmp.prn_qty,0) as QtyReserved , 0 as qtyAllocated,
    0 as call_allocation_sp
	from (select  sum(tbl_production_plans_process.qty) as prn_qty
	,tbl_production_plans_process.product_id
	,(select tbl_prns.location_id
	from tbl_prns
	where tbl_prns.prn_id = p_TransactionID ) as location_id
	from tbl_production_plans_process
	inner join tbl_production_plans_contracts on tbl_production_plans_contracts.plan_id = tbl_production_plans_process.plan_id
	where tbl_production_plans_contracts.contract_id in (
	select tbl_prns_contracts.contract_id
	from tbl_prns_contracts
	where tbl_prns_contracts.prn_id = p_TransactionID )
	and tbl_production_plans_process.is_row_material = 1
	group by tbl_production_plans_process.product_id ) tmp;
  
elseif(p_TransactionType = 'PO')
then
	
	insert into InventoryList
	select product_id as ProductID , ifnull(location_id,0) as LocationID , 0 as ContainerID, 
	IFNULL(qty,0) as OnOrderQty ,0 as QtyInStock,0 as QtyReserved, 0 as qtyAllocated,
    0 as call_allocation_sp
	from tbl_po_products
    inner join tbl_pos on tbl_pos.po_id = tbl_po_products.po_id
	where tbl_pos.po_id = p_TransactionID;
		
elseif (p_TransactionType = 'GRN')
then


	insert into InventoryList
	select product_id as ProductID , ifnull(location_id,0) as LocationID , 
	0 as ContainerID, 0 as OnOrderQty ,IFNULL(qty,0) as QtyInStock,0 as QtyReserved, 0 as qtyAllocated,0 as call_allocation_sp
	from tbl_grn_products
    inner join tbl_grns on tbl_grns.grn_id = tbl_grn_products.grn_id
	where tbl_grn_products.grn_id = p_TransactionID;
    
    -- // deduct onorder Qty
    insert into InventoryList
	select product_id as ProductID , ifnull(location_id,0) as LocationID , 
	0 as ContainerID, IFNULL(qty,0) *-1 as OnOrderQty , 0 as QtyInStock,0 as QtyReserved, 0 as qtyAllocated,0 as call_allocation_sp
	from tbl_grn_products
    inner join tbl_grns on tbl_grns.grn_id = tbl_grn_products.grn_id
	where tbl_grn_products.grn_id = p_TransactionID;
    
elseif (p_TransactionType = 'SAMPLES')
then
 
	insert into InventoryList
	select tbl_sample_products.product_id as ProductID , ifnull(tbl_samples.location_id,0) as LocationID , 
	0 as ContainerID, 0 as OnOrderQty ,IFNULL(tbl_sample_products.qty,0)*-1 as QtyInStock,0 as QtyReserved, 0 as qtyAllocated,0 as call_allocation_sp
	from tbl_sample_products
	inner join tbl_samples on tbl_samples.sample_id = tbl_sample_products.sample_id
	where tbl_sample_products.sample_id = p_TransactionID;
    
elseif (p_TransactionType = 'DAMAGES')
then
 
	insert into InventoryList
	select tbl_damage_products.product_id as ProductID , ifnull(tbl_damage.location_id,0) as LocationID , 
	0 as ContainerID, 0 as OnOrderQty ,IFNULL(tbl_damage_products.qty,0)*-1 as QtyInStock,0 as QtyReserved, 0 as qtyAllocated,0 as call_allocation_sp
	from tbl_damage_products
	inner join tbl_damage on tbl_damage.sample_id = tbl_damage_products.sample_id
	where tbl_damage_products.sample_id = p_TransactionID;
 		
 		

elseif (p_TransactionType = 'GRNReturn')
then
 
	insert into InventoryList
	select product_id as ProductID , ifnull(tbl_grn_return.location_id,0) as LocationID , 
	0 as ContainerID, 0 as OnOrderQty ,IFNULL(qty,0)*-1 as QtyInStock,0 as QtyReserved, 0 as qtyAllocated,0 as call_allocation_sp
	from tbl_grn_return_products
    inner join tbl_grn_return on tbl_grn_return.grn_return_id = tbl_grn_return_products.grn_return_id
	where tbl_grn_return_products.grn_return_id = p_TransactionID;
    
    
elseif (p_TransactionType = 'Sales')
then
    insert into InventoryList
	select product_id as ProductID , 0 as LocationID , 0 as ContainerID, 
	0 as OnOrderQty ,0 as QtyInStock,IFNULL(ContractQty,0) as QtyReserved, 0 as qtyAllocated,1 as call_allocation_sp
	from tbl_so_products
	where so_id = p_TransactionID;
    
 elseif (p_TransactionType = 'dispatch')
then
    insert into InventoryList
	select product_id as ProductID , 0 as LocationID , 0 as ContainerID, 
	0 as OnOrderQty ,IFNULL(qty,0)*-1  as QtyInStock,IFNULL(qty,0)*-1 as QtyReserved, IFNULL(qty,0)*-1  as qtyAllocated,0 as call_allocation_sp
	from tbl_dispatch_products
	where dispatch_id = p_TransactionID;
    
    -- update sales products dispatch qty
	update    tbl_so_products    
	inner join tbl_dispatch_products on tbl_so_products.so_product_id = tbl_dispatch_products.so_product_id
	and tbl_so_products.product_id = tbl_dispatch_products.product_id
	set tbl_so_products.dispatch_qty = ifnull( tbl_so_products.dispatch_qty,0) + qty
	where dispatch_id = p_TransactionID;
    
elseif (p_TransactionType = 'production_plan')
then
 
		 -- deduct row meirials
		  insert into InventoryList
		 select tbl_products.product_id as ProductID , 0 as LocationID , 0 as ContainerID, 
		 0 as OnOrderQty ,IFNULL(tbl_production_plans_process.mrn_qty,0)*-1 as QtyInStock,
         case when length(tree_id) >1 then IFNULL(tbl_production_plans_process.qty,0)*-1 else 0 end as QtyReserved,
		 case when length(tree_id) >1 then IFNULL(tbl_production_plans_process.mrn_qty,0)*-1 else 0 end as qtyAllocated ,
         0 as call_allocation_sp
		 from tbl_production_plans_process 
		 inner join tbl_products on tbl_products.product_id = tbl_production_plans_process.product_id 
		 where tbl_production_plans_process.plan_id = (select plan_id from tbl_production_plans_process where id = p_TransactionID) 
		 and tbl_production_plans_process.boqId  in (
											select boq_id from tbl_boq  
											where product_id = p_secondID) 
		 and length(tree_id) > 1;
         
         -- update parent instock * Allocation will be update with allocation SP
		  insert into InventoryList
		 select tbl_production_plans_process.product_id as ProductID , 0 as LocationID , 0 as ContainerID, 
		 0 as OnOrderQty ,IFNULL(tbl_production_plans_process.qty,0)  as QtyInStock,
         case when length(tree_id) >1 then IFNULL(tbl_production_plans_process.qty,0) else 0 end as QtyReserved 
         ,0 as qtyAllocated ,  1 as call_allocation_sp
         from tbl_production_plans_process    where tree_id in (      
		 select distinct 
		 SUBSTRING(tbl_production_plans_process.tree_id , 1, length(tbl_production_plans_process.tree_id )-2) as perent_tree 
		 from tbl_production_plans_process 
		 inner join tbl_products on tbl_products.product_id = tbl_production_plans_process.product_id 
		 where tbl_production_plans_process.plan_id = (select plan_id from tbl_production_plans_process where id = p_TransactionID) 
		 and tbl_production_plans_process.boqId  in (
											select boq_id from tbl_boq  
											where product_id = p_secondID) )
         and tbl_production_plans_process.plan_id = (select plan_id from tbl_production_plans_process where id = p_TransactionID)   ;
         
elseif (p_TransactionType = 'mrn_issue')
then

	set @mrn_issue_type:= '';
	select 	mrn_issue_type
		into @mrn_issue_type  
		from tbl_mrn_issues 
		where mrn_issue_id = p_TransactionID;
			
            if(@mrn_issue_type = 'WITH_PRODUCTION_PLAN') then
				insert into InventoryList
				select product_id as ProductID , 0 as LocationID , 0 as ContainerID, 
				0 as OnOrderQty ,0 as QtyInStock,0 as QtyReserved, 0 as qtyAllocated,
				 1 as call_allocation_sp
				from tbl_mrn_issue_products 
				where mrn_issue_id = p_TransactionID;
            else 
				insert into InventoryList
				select product_id as ProductID , 0 as LocationID , 0 as ContainerID, 
				0 as OnOrderQty , qty*-1 as QtyInStock,0 as QtyReserved, 0 as qtyAllocated,
				 0 as call_allocation_sp
				from tbl_mrn_issue_products 
				where mrn_issue_id = p_TransactionID;
			end if;


    

elseif (p_TransactionType = 'mrn')
then

set @mrn_type:= '';
	select 	mrn_type
		into @mrn_type  
		from tbl_mrns 
		where mrn_id = p_TransactionID;

		if(@mrn_type = 'WITH_PRODUCTION_PLAN') then
		  update    tbl_mrns    
			inner join tbl_mrn_products on tbl_mrn_products.mrn_id = tbl_mrns.mrn_id
			inner join tbl_production_plans_process on tbl_production_plans_process.plan_id =  tbl_mrns.plan_id
			and tbl_mrn_products.product_id = tbl_production_plans_process.product_id
			set  tbl_production_plans_process.mrn_qty =  tbl_mrn_products.qty  
			where tbl_mrns.mrn_id = p_TransactionID and tbl_mrns.status = 4;
       end if; 
        
end if;

 -- select * from InventoryList;

OPEN _cursor; 
	getEmail: LOOP
		FETCH _cursor INTO   v_productID ,v_LocationID ,v_ContainerID ,v_OnOrderQty ,v_QtyInStock ,v_QtyReserved,v_QtyAllocated,v_call_allocation_sp;
		IF finished = 1 THEN  
			LEAVE getEmail;
		END IF;
        
        set @count:=0; 
		select  count(*) into @count from tbl_inventory 
        where product_id = v_productID and  location_id = v_LocationID and container_id = v_ContainerID;
        
       if(@count>0)
		then
		-- print @QtyInStock
			 UPDATE tbl_inventory
			   SET `qty_in_stock` =  qty_in_stock + v_QtyInStock
				  ,`qty_on_order` = qty_on_order + v_OnOrderQty
				  ,`qty_reserved` = qty_reserved +v_QtyReserved
                  ,`qty_allocated` = qty_allocated +v_QtyAllocated 
				  ,`modified_by` = p_User
				  ,`modified_date_time` = NOW(3)
			 where product_id = v_productID and  location_id =  v_LocationID and container_id = v_ContainerID;
  
		else
		-- print 2
			INSERT INTO tbl_inventory (`location_id`,`container_id`,`product_id`,
			`qty_in_stock`,`qty_on_order`,`qty_reserved`, `created_by`,`created_date_time`,
            `modified_by`,`modified_date_time`,`qty_allocated` )
			 VALUES
			   (v_LocationID
			   ,v_ContainerID
			   ,v_productID
			   ,v_QtyInStock
			   ,v_OnOrderQty 
			   ,v_QtyReserved 
			   ,p_User
			   ,NOW(3)
			   ,p_User
			   ,NOW(3)
               ,v_QtyAllocated);
  
		end if;
        
      -- select v_productID ,v_LocationID ,v_ContainerID ,v_OnOrderQty ,v_QtyInStock ,v_QtyReserved;
      
          if (v_call_allocation_sp = 1) then
       
			set @p_TransactionType = p_TransactionType;
			set @p_TransactionID = p_TransactionID;
			set @p_productID = v_productID;
			set @p_LocationID = v_LocationID;
			set @p_User = p_User;
		 	call Update_allocation_qty(@p_TransactionType, @p_TransactionID, @p_productID, @p_LocationID, @p_User);
            	end if;
            
            call update_trn_status();
            
	 -- update log table
    INSERT INTO tbl_inventory_log (`location_id`,`container_id`,`product_id`,
	`qty_in_stock`,`qty_on_order`,`qty_reserved`, `created_by`,`created_date_time`,
	`modified_by`,`modified_date_time`,`qty_allocated`,`transaction_type`,`transaction_id` )
	 VALUES
	   (v_LocationID
	   ,v_ContainerID
	   ,v_productID
	   ,v_QtyInStock
	   ,v_OnOrderQty 
	   ,v_QtyReserved 
	   ,p_User
	   ,NOW(3)
	   ,p_User
	   ,NOW(3)
	   ,v_QtyAllocated
	   ,p_TransactionType
	   ,p_TransactionID
       );
		
        
END LOOP getEmail;
CLOSE _cursor;

  end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Update_allocation_qty` (IN `p_TransactionType` VARCHAR(50), IN `p_TransactionID` INT, IN `p_productID` INT, IN `p_LocationID` INT, IN `p_User` VARCHAR(50))  BEGIN

declare v_ID int;
declare v_tree_id varchar(50);
declare v_qty decimal(18,6); 
declare v_allocsated_qty decimal(18,6); 
DECLARE finished INTEGER DEFAULT 0; 

DECLARE _cursor CURSOR FOR select id,tree_id,qty,allocated_qty from tbl_production_plans_process
inner join tbl_production_plans on tbl_production_plans.plan_id = tbl_production_plans_process.plan_id
where product_id = p_productID  and allocated_qty <> qty and length(tree_id) > 1
order by tbl_production_plans.plan_date ASc , tree_id desc;
 
DECLARE _cursor_sale CURSOR FOR select so_product_id as id,tbl_so.so_id,ContractQty,allocated_qty from tbl_so_products
inner join tbl_so on tbl_so.so_id = tbl_so_products.so_id
where product_id = p_productID  and allocated_qty <> ContractQty
order by tbl_so.so_date ASc  ;


DECLARE _cursor_mrn_issue CURSOR FOR   
select tbl_production_plans_process.id,tree_id,tbl_mrn_issue_products.qty,allocated_qty from tbl_mrn_issue_products 
inner join tbl_production_plans_process on tbl_production_plans_process.plan_id =  tbl_mrn_issue_products.plan_id
and tbl_mrn_issue_products.product_id = tbl_production_plans_process.product_id
where mrn_issue_id = p_TransactionID and  tbl_mrn_issue_products.product_id = p_productID; 

DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1; 
 
-- - production plan ------------------------------------------------------------------------------------
  if(p_TransactionType = 'production_plan')then
-- Row items
 OPEN _cursor; 
	getEmail: LOOP
		FETCH _cursor INTO   v_ID ,v_tree_id ,v_qty,v_allocsated_qty;
		IF finished = 1 THEN  
			LEAVE getEmail;
		END IF;
        
set @balance:=0; 
select ifnull(qty_in_stock,0) - ifnull(qty_allocated,0)  into @balance from tbl_inventory 
where product_id = p_productID and  location_id = p_LocationID ;-- and container_id = 0;
    --  select @balance;
	if( ifnull(@balance,0)>0) then
		
 
        
        set @update_allocated_qty=0; 
        set @update_allocated_qty=v_qty-v_allocsated_qty; 
        
        if(@update_allocated_qty> @balance) then
				update tbl_production_plans_process  set allocated_qty = allocated_qty+  @balance ,
                  mrn_qty = allocated_qty+  @balance  -- for enable production start button
					where id = v_ID;
                
				update tbl_inventory set qty_allocated = qty_allocated+  @balance
					where product_id = p_productID and  location_id = p_LocationID ;
                
		elseif(@update_allocated_qty <= @balance) then
				update tbl_production_plans_process 
                set allocated_qty = allocated_qty+  @update_allocated_qty  , -- for enable production start button
                 mrn_qty = mrn_qty+  @update_allocated_qty  
                where id = v_ID;
                
                update tbl_inventory set qty_allocated = qty_allocated+   @update_allocated_qty
					where product_id = p_productID and  location_id = p_LocationID ;
        end if;
        
        

        
       
	end if;
 END LOOP getEmail;
 CLOSE _cursor;
  end if;


-- - MRN Issue ------------------------------------------------------------------------------------
 set finished = 0;
 if(p_TransactionType = 'mrn_issue')then
-- Row items
 OPEN _cursor_mrn_issue; 
	getEmail: LOOP
		FETCH _cursor_mrn_issue INTO   v_ID ,v_tree_id ,v_qty,v_allocsated_qty;
		IF finished = 1 THEN  
			LEAVE getEmail;
		END IF;
        
set @balance:=0; 
select ifnull(qty_in_stock,0) - ifnull(qty_allocated,0)  into @balance from tbl_inventory 
where product_id = p_productID and  location_id = p_LocationID ;-- and container_id = 0;
    --  select @balance;
	if( ifnull(@balance,0)>0) then
		
 
        
        set @update_allocated_qty=0; 
        set @update_allocated_qty=v_qty; 
        
        if(@update_allocated_qty> @balance) then
				update tbl_production_plans_process  set allocated_qty = allocated_qty+  @balance 
					where id = v_ID;
                
				update tbl_inventory set qty_allocated = qty_allocated+  @balance
					where product_id = p_productID and  location_id = p_LocationID ;
                
		elseif(@update_allocated_qty <= @balance) then
				update tbl_production_plans_process 
                set allocated_qty = allocated_qty + @update_allocated_qty  where id = v_ID;
                
                update tbl_inventory set qty_allocated = qty_allocated+   @update_allocated_qty
					where product_id = p_productID and  location_id = p_LocationID ;
        end if;
        
        

        
       
	end if;
 END LOOP getEmail;
 CLOSE _cursor_mrn_issue;
  end if;

-- Sales Item--------------------------------------------------------------------------------------------
 set finished = 0;
 
  OPEN _cursor_sale; 
	getfinesh: LOOP
		FETCH _cursor_sale INTO   v_ID ,v_tree_id ,v_qty,v_allocsated_qty;
		IF finished = 1 THEN  
			LEAVE getfinesh;
		END IF;
        
set @balance:=0; 
select ifnull(qty_in_stock,0) - ifnull(qty_allocated,0)  into @balance from tbl_inventory 
where product_id = p_productID and  location_id = p_LocationID ;-- and container_id = 0;
    --  select @balance;
	if( ifnull(@balance,0)>0) then
		
 
        
        set @update_allocated_qty=0; 
        set @update_allocated_qty=v_qty-v_allocsated_qty; 
        
        if(@update_allocated_qty> @balance) then
				update tbl_so_products  set allocated_qty =  allocated_qty+  @balance 
					where so_product_id = v_ID;
                
				update tbl_inventory set qty_allocated = qty_allocated+  @balance
					where product_id = p_productID and  location_id = p_LocationID ;
                
		elseif(@update_allocated_qty <= @balance) then
				update tbl_so_products 
                set allocated_qty = @update_allocated_qty  
                where so_product_id = v_ID;
                
                update tbl_inventory set qty_allocated = qty_allocated+   @update_allocated_qty
					where product_id = p_productID and  location_id = p_LocationID ;
        end if;
        
        

        
       
	end if;
 END LOOP getfinesh;
 CLOSE _cursor_sale;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_production_status` ()  BEGIN
-- production Order
UPDATE tbl_production_plans AS b
INNER JOIN  
 (
select  plan_id  ,
case when sum(qty)  = sum(allocated_qty) then 4 -- fineshed
	 when sum(qty) > 0  and sum(allocated_qty) = 0 then 3 -- Wating
     when sum(qty) > 0  and sum(allocated_qty) >0 and sum(qty) != sum(allocated_qty) then 2 end as status
 from tbl_production_plans_process
 where length(tree_id) > 1 
 group by plan_id) tmp on  tmp.plan_id = b.plan_id
 SET b.status = tmp.status;
 
 -- Sale
UPDATE tbl_so AS b
INNER JOIN  
 (
 select  tbl_so_products.so_id  ,sum(ContractQty) , sum(dispatch_qty),
case when   ifnull(is_invoiced,0) = 1 and  sum(dispatch_qty) = sum(allocated_qty)  then 11
	 when   ifnull(is_invoiced,0) = 1 and  sum(dispatch_qty) != sum(allocated_qty)  then 10
	 when sum(ContractQty)  = sum(allocated_qty) and ifnull(is_invoiced,0) = 1 then 9
	 when sum(ContractQty)  = sum(allocated_qty) then 8 -- fineshed 
	 when sum(ContractQty) > 0  and sum(allocated_qty) = 0 then 6 -- Wating
     when sum(ContractQty) > 0  and sum(allocated_qty) >0 and sum(ContractQty) != sum(allocated_qty) then 7 end as status
 from tbl_so_products
  inner join tbl_so on tbl_so.so_id = tbl_so_products.so_id
  group by tbl_so_products.so_id) tmp on  tmp.so_id = b.so_id
 SET b.status = tmp.status;
   
   
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_trn_status` ()  BEGIN
-- production Order
UPDATE tbl_production_plans AS b
INNER JOIN  
 (
select  plan_id  ,
case when (sum(mrn_qty)  = sum(allocated_qty) and sum(case when is_row_material = 0 
and is_process_finished = 1 then 1 else 0 end) =sum(case when is_row_material = 0  then 1 else 0 end))  then 4 -- fineshed
	 when sum(mrn_qty) > 0  and sum(allocated_qty) = 0 then 3 -- Wating
     when sum(mrn_qty) > 0  and sum(allocated_qty) >0 and sum(qty) != sum(allocated_qty) then 2 end as status
 from tbl_production_plans_process
 -- where length(tree_id) > 1 
 group by plan_id) tmp on  tmp.plan_id = b.plan_id
 SET b.status = tmp.status
 where b.status not in ('4');
 
 -- Sale
UPDATE tbl_so AS b
INNER JOIN  
 (
 select  tbl_so_products.so_id  ,sum(ContractQty) , sum(dispatch_qty),
case  when sum(ContractQty)  = sum(allocated_qty) and sum(dispatch_qty) = 0 then 8 -- fineshed 

when     sum(dispatch_qty) = sum(allocated_qty)  then 11
	 when     sum(dispatch_qty) != sum(allocated_qty)  then 10
	 when sum(ContractQty)  = sum(allocated_qty) and ifnull(is_invoiced,0) = 1 then 9
	
	 when sum(ContractQty) > 0  and sum(allocated_qty) = 0 then 6 -- Wating
     when sum(ContractQty) > 0  and sum(allocated_qty) >0 and sum(ContractQty) != sum(allocated_qty) then 7 end as status
 from tbl_so_products
  inner join tbl_so on tbl_so.so_id = tbl_so_products.so_id
  group by tbl_so_products.so_id) tmp on  tmp.so_id = b.so_id
 SET b.status = tmp.status;
   
   
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `extra_medicine`
--

CREATE TABLE `extra_medicine` (
  `id` int(11) NOT NULL,
  `extramedicinename` text NOT NULL,
  `extradosage` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `createdon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `extra_specialty`
--

CREATE TABLE `extra_specialty` (
  `id` int(11) NOT NULL,
  `especialty` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `extra_specialty`
--

INSERT INTO `extra_specialty` (`id`, `especialty`) VALUES
(1, 'werrrr'),
(2, 'qwer'),
(3, 'ssss');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_channelling`
--

CREATE TABLE `tbl_channelling` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone_no` varchar(100) NOT NULL,
  `email` varchar(500) NOT NULL,
  `channel_date` date NOT NULL,
  `specialty` varchar(1000) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `message` varchar(500) NOT NULL,
  `channel_time` varchar(100) NOT NULL,
  `fee` decimal(16,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `status` varchar(100) NOT NULL,
  `token` varchar(10) NOT NULL DEFAULT 'N/A',
  `doctor_notes` varchar(5000) NOT NULL,
  `blood_pressure` decimal(11,2) NOT NULL,
  `heart_rate` decimal(11,2) NOT NULL,
  `blood_suger` decimal(11,2) NOT NULL,
  `cholesterol` decimal(11,2) NOT NULL,
  `height` decimal(11,2) NOT NULL,
  `weight` decimal(11,2) NOT NULL,
  `is_next_channel` tinyint(1) NOT NULL DEFAULT 0,
  `next_channeling_date` date NOT NULL DEFAULT '2021-01-01',
  `type` varchar(100) NOT NULL,
  `meeting_type` varchar(100) NOT NULL,
  `meeting_url` varchar(100) NOT NULL,
  `meeting_user_id` varchar(100) NOT NULL,
  `meeting_password` varchar(100) NOT NULL,
  `abdomen` varchar(500) NOT NULL,
  `respiration` varchar(500) NOT NULL,
  `nervousSystem` varchar(500) NOT NULL,
  `pmh` varchar(500) NOT NULL,
  `notification_date` date NOT NULL DEFAULT '2021-01-01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_channelling`
--

INSERT INTO `tbl_channelling` (`id`, `name`, `phone_no`, `email`, `channel_date`, `specialty`, `doctor_id`, `message`, `channel_time`, `fee`, `user_id`, `created_by`, `created_on`, `status`, `token`, `doctor_notes`, `blood_pressure`, `heart_rate`, `blood_suger`, `cholesterol`, `height`, `weight`, `is_next_channel`, `next_channeling_date`, `type`, `meeting_type`, `meeting_url`, `meeting_user_id`, `meeting_password`, `abdomen`, `respiration`, `nervousSystem`, `pmh`, `notification_date`) VALUES
(1, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-07-21', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-07-20 09:56:37', 'Confirmed', '1', '', '110.00', '80.00', '120.00', '118.00', '153.00', '60.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(2, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-07-24', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-07-24 11:52:44', 'Confirmed', '1', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(3, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-07-26', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-07-25 15:40:50', 'Confirmed', '2', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(4, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-07-26', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-07-25 15:43:52', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Online', 'Google meeting', '', '', '', '', '', '', '', '2021-01-01'),
(5, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-07-26', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-07-25 16:19:34', 'New', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Online', '', '', '', '', '', '', '', '', '2021-01-01'),
(6, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-07-29', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-07-28 19:10:58', 'Confirmed', '1', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(7, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-07-29', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-07-29 17:56:22', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Online', 'Zoom', '', '', '', '', '', '', '', '2021-01-01'),
(8, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-07-29', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-07-29 17:56:39', 'Confirmed', '2', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(9, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-07-29', '0', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-07-29 17:58:27', 'New', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(10, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-07-29', '0', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-07-29 18:13:42', 'New', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(11, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-08-07', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-08-07 16:53:48', 'Cancelled', '1', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(12, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-08-07', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-08-07 16:54:01', 'Confirmed', '2', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(13, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-08-07', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-08-07 16:54:33', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Online', 'Zoom', '', '', '', '', '', '', '', '2021-01-01'),
(14, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-08-07', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-08-07 17:08:01', 'Confirmed', '3', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(15, 'Amanda Illangakoon', '0713236461', 'amanda.illangakoon@gmail.com', '2021-08-12', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 0, 0, '2021-08-12 11:48:08', 'Confirmed', '1', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(16, 'Amanda Illangakoon', '0713236461', 'amanda.illangakoon@gmail.com', '2021-08-12', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 0, 0, '2021-08-12 11:49:41', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Online', 'Zoom', '', '', '', '', '', '', '', '2021-01-01'),
(17, 'Amanda Illangakoon', '0713236461', 'amanda.illangakoon@gmail.com', '2021-08-12', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 0, 0, '2021-08-12 11:50:08', 'Confirmed', '4', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(18, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-08-12', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-08-12 15:03:20', 'Confirmed', '5', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(19, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-08-14', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-08-13 21:33:50', 'Doctor checked', '1', 'sample', '120.00', '110.00', '125.00', '115.00', '159.00', '58.00', 1, '2021-09-29', 'Visit', '', '', '', '', '', '', '', '', '2021-09-27'),
(20, 'Amanda Illangakoon', '0713236461', 'amanda.illangakoon@gmail.com', '2021-08-14', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 0, 0, '2021-08-14 09:13:41', 'Confirmed', '1', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(21, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-08-14', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-08-14 10:54:48', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Online', 'Whats app', '', '', '', '', '', '', '', '2021-01-01'),
(22, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-08-17', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-08-14 10:58:08', 'Confirmed', '1', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(23, 'Amanda Illangakoon', '0713236461', 'amanda.illangakoon@gmail.com', '2021-08-23', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 0, 0, '2021-08-21 11:46:53', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Online', 'Whats app', '', '', '', '', '', '', '', '2021-01-01'),
(24, 'Amanda Illangakoon', '0713236461', 'amanda.illangakoon@gmail.com', '2021-08-30', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 0, 0, '2021-08-29 15:25:54', 'New', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(25, 'Amanda Illankoon', '0761418475', 'amanda.illangakoon@gmail.com', '2021-08-30', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-08-29 15:41:15', 'Confirmed', '1', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2021-09-28', 'Visit', '', '', '', '', '', '', '', '', '2021-09-27'),
(26, 'Amanda Illangakoon', '0713236461', 'amanda.illangakoon@gmail.com', '2021-09-06', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 0, 0, '2021-09-05 13:48:10', 'Doctor checked', '3', 'testing', '110.00', '80.00', '140.00', '120.00', '153.00', '54.00', 0, '2021-09-06', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(27, 'Amanda Illangakoon', '0713236461', 'amanda.illangakoon@gmail.com', '2021-09-06', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 0, 0, '2021-09-05 13:48:10', 'Confirmed', '1', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(28, 'Amanda Illangakoon', '0713236461', 'amanda.illangakoon@gmail.com', '2021-09-06', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 0, 0, '2021-09-06 04:32:57', 'Doctor checked', '2', 'testing', '105.00', '85.00', '135.00', '140.00', '153.00', '58.00', 0, '2021-09-06', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(29, 'Amanda Illangakoon', '0713236461', 'amanda.illangakoon@gmail.com', '2021-09-06', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 0, 0, '2021-09-06 05:07:23', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Online', 'Zoom', '', '', '', '', '', '', '', '2021-01-01'),
(30, 'Amanda Illangakoon', '0713236461', 'amanda.illangakoon@gmail.com', '2021-09-07', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 0, 0, '2021-09-07 10:06:14', 'Confirmed', '1', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(31, 'Amanda Illankoon', '0770455618', 'amanda.illangakoon@gmail.com', '2021-09-07', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-09-07 10:09:42', 'Doctor checked', '2', 'test', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2021-09-08', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(32, 'Amanda Illangakoon', '0761418475', 'amanda.illangakoon@gmail.com', '2021-09-13', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 0, '2021-09-10 20:36:04', 'Doctor checked', '1', 'test', '122.00', '110.00', '123.00', '114.00', '15359.00', '0.00', 1, '2021-09-18', 'Visit', '', '', '', '', '', '', '', '', '2021-09-20'),
(33, 'Amanda Illangakoon', '0761418475', 'amanda.illangakoon@gmail.com', '2021-09-13', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 0, '2021-09-10 20:37:23', 'Cancelled', '2', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(34, 'Amanda Illankoon', '0761418475', 'amanda.illangakoon@gmail.com', '2021-09-13', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-09-10 21:04:01', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(35, 'Amanda Illangakoon', '0761418475', 'amanda.illangakoon@gmail.com', '2021-09-11', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 0, '2021-09-11 07:43:54', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Online', '', '', '', '', '', '', '', '', '2021-01-01'),
(36, 'Amanda Illankoon', '0761418475', 'amanda.illangakoon@gmail.com', '2021-09-18', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-09-18 17:09:39', 'Confirmed', '1', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(37, 'abc', '0770455618', 'abc@gmail.com', '2021-09-20', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 7, 7, '2021-09-20 12:28:35', 'New', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(38, 'abc', '0770455618', 'abc@gmail.com', '2021-09-20', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 7, 7, '2021-09-20 12:29:09', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(39, 'Amanda Illangakoon', '0761418475', 'amanda.illangakoon@gmail.com', '2021-09-25', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 0, '2021-09-24 20:37:18', 'Confirmed', '1', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-09-25'),
(40, 'Amanda Illangakoon', '0761418475', 'amanda.illangakoon@gmail.com', '2021-09-27', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 0, '2021-09-26 12:59:04', 'Confirmed', '1', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-09-27'),
(41, 'Amanda Illankoon', '0770455618', 'harshaatefac@gmail.com', '2021-09-27', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 0, 0, '2021-09-26 22:16:24', 'Confirmed', '2', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-09-27'),
(42, 'Harsha Nawarathna', '0770455618', 'harshaatefac@gmail.com', '2021-09-27', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 9, 1, '2021-09-27 09:09:10', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Online', 'Zoom', '', '', '', '', '', '', '', '2021-09-27'),
(43, 'Amanda Illangakoon', '0761418475', 'amanda.illangakoon@gmail.com', '2021-09-27', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 0, '2021-09-27 13:57:28', 'Confirmed', '3', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-09-27'),
(44, 'Kamal Illangakoon', '0770455618', 'kamal@gmail.com', '2021-09-28', 'Orthopaedics', 5, '', '04:00PM to 06:00PM', '1500.00', 6, 1, '2021-09-27 14:33:34', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-09-27'),
(45, 'Amanda Illankoon', '0761418475', 'amanda.illangakoon@gmail.com', '2021-09-27', 'Pediatrics', 1, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-09-27 18:47:04', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(46, 'Amanda Illankoon', '0761418475', 'amanda.illangakoon@gmail.com', '2021-09-27', 'Cardiology', 2, '', '04:00PM to 06:00PM', '1500.00', 2, 2, '2021-09-27 18:47:44', 'Doctor checked', 'N/A', 'test', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-09-27', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01'),
(47, 'Abc', '0770455618', 'abc1@gmail.com', '2021-09-27', 'Cardiology', 2, '', '04:00PM to 06:00PM', '1500.00', 12, 12, '2021-09-27 18:50:51', 'Confirmed', 'N/A', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2021-01-01', 'Visit', '', '', '', '', '', '', '', '', '2021-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_channelling_doc`
--

CREATE TABLE `tbl_channelling_doc` (
  `id` int(11) NOT NULL,
  `doc_path` varchar(500) NOT NULL,
  `doc` varchar(500) NOT NULL,
  `note` varchar(5000) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_channelling_doc`
--

INSERT INTO `tbl_channelling_doc` (`id`, `doc_path`, `doc`, `note`, `channel_id`, `patient_id`) VALUES
(1, 'documents/patient/1/channelling_doc/channelling_doc_32_1.jpg', 'sample', '', 32, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chat`
--

CREATE TABLE `tbl_chat` (
  `id` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `msg` varchar(5000) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `is_medical_center` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_doctors`
--

CREATE TABLE `tbl_doctors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `specialty` varchar(5000) NOT NULL,
  `channelling_fee` decimal(16,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `title` varchar(10) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `nic` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `chkMonday` tinyint(1) NOT NULL DEFAULT 0,
  `avlMonday` varchar(100) NOT NULL,
  `chkTuesday` tinyint(1) NOT NULL DEFAULT 0,
  `avlTuesday` varchar(100) NOT NULL,
  `chkWednesday` tinyint(1) NOT NULL DEFAULT 0,
  `avlWednesday` varchar(100) NOT NULL,
  `chkThursday` tinyint(1) NOT NULL DEFAULT 0,
  `avlThursday` varchar(100) NOT NULL,
  `chkFriday` tinyint(1) NOT NULL DEFAULT 0,
  `avlFriday` varchar(100) NOT NULL,
  `chkSaturday` tinyint(1) NOT NULL DEFAULT 0,
  `avlSaturday` varchar(100) NOT NULL,
  `chkSunday` tinyint(1) NOT NULL DEFAULT 0,
  `avlSunday` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_doctors`
--

INSERT INTO `tbl_doctors` (`id`, `user_id`, `created_by`, `created_on`, `specialty`, `channelling_fee`, `is_active`, `title`, `gender`, `nic`, `address`, `chkMonday`, `avlMonday`, `chkTuesday`, `avlTuesday`, `chkWednesday`, `avlWednesday`, `chkThursday`, `avlThursday`, `chkFriday`, `avlFriday`, `chkSaturday`, `avlSaturday`, `chkSunday`, `avlSunday`) VALUES
(1, 3, 0, '0000-00-00 00:00:00', 'Pediatrics', '1500.00', 1, 'Mr.', 'Male', '8278654322V', 'No. 20', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM'),
(2, 4, 0, '0000-00-00 00:00:00', 'Cardiology', '1500.00', 1, 'Mr.', 'Female', '8278654322V', 'No. 101/ A/4, Malabe road, Kothalawala,', 1, '04:00PM to 06:00PM', 0, '', 0, '', 0, '', 0, '', 0, '', 0, ''),
(4, 10, 1, '2021-09-27 11:34:59', 'Neurologists', '1500.00', 1, 'Mr.', 'Male', '932761231V', 'No. 101/ A/4, Malabe road, Kothalawala,', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM'),
(5, 11, 1, '2021-09-27 11:36:36', 'Orthopaedics', '1500.00', 1, 'Mr.', 'Female', '867381234V', 'No. 101/ A/4, Malabe road, Kothalawala,', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM', 1, '04:00PM to 06:00PM');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_medical_reports`
--

CREATE TABLE `tbl_medical_reports` (
  `id` int(11) NOT NULL,
  `report` varchar(500) NOT NULL,
  `note` varchar(5000) NOT NULL,
  `doc_path` varchar(1000) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_medical_reports`
--

INSERT INTO `tbl_medical_reports` (`id`, `report`, `note`, `doc_path`, `created_by`, `created_on`, `patient_id`) VALUES
(1, 'Cholesterol', '', 'documents/patient/1/medical_reports/medical_reports_0_1.jpeg', 1, '2021-09-27 17:08:53', 1),
(2, 'Fasting Blood Sugar', '', 'documents/patient/5/medical_reports/medical_reports_0_2.jpeg', 1, '2021-08-15 11:55:19', 5),
(3, 'TSH Report', '', 'documents/patient/2/medical_reports/medical_reports_0_3.jpeg', 1, '2021-09-27 12:05:20', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_medical_tests`
--

CREATE TABLE `tbl_medical_tests` (
  `id` int(11) NOT NULL,
  `test_name` varchar(1000) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `channeling_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_medical_tests`
--

INSERT INTO `tbl_medical_tests` (`id`, `test_name`, `note`, `channeling_id`, `created_by`, `created_on`) VALUES
(1, 'tst', 'tst', 23, 3, '2021-08-21 12:21:03'),
(2, 'sample', '', 25, 3, '2021-08-29 15:45:05'),
(3, 'tsttst', 'tst', 29, 3, '2021-09-06 05:18:48'),
(4, 'test', 'tst', 35, 3, '2021-09-11 07:45:46');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_medicine`
--

CREATE TABLE `tbl_medicine` (
  `id` int(11) NOT NULL,
  `frequency` varchar(100) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `dosage` varchar(100) NOT NULL,
  `medicine_name` varchar(1000) NOT NULL,
  `channeling_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_medicine`
--

INSERT INTO `tbl_medicine` (`id`, `frequency`, `note`, `dosage`, `medicine_name`, `channeling_id`, `created_by`, `created_on`) VALUES
(1, 'bd', 'tst', 'bd', 'test', 23, 3, '2021-08-21 12:18:04'),
(2, 'bd', 'ss', '5mg', 'test', 25, 3, '2021-08-29 15:44:53'),
(3, 'tst', 'tst', 'tst', 'tstts', 29, 3, '2021-09-06 05:18:19'),
(4, 'tst', 'tst', 'tst', 'test', 35, 3, '2021-09-11 07:45:35'),
(9, 'a', 'a', 'a', 'a', 36, 3, '2021-09-18 18:00:19'),
(10, 'bd', 'tes', '10mg', 'test', 46, 4, '2021-09-27 18:54:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_patients`
--

CREATE TABLE `tbl_patients` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `note` varchar(5000) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `title` varchar(10) NOT NULL,
  `nic` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `birth_day` date NOT NULL DEFAULT '2000-01-01',
  `blood_group` varchar(50) NOT NULL,
  `allergies` varchar(1000) NOT NULL,
  `special_concern` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_patients`
--

INSERT INTO `tbl_patients` (`id`, `user_id`, `created_on`, `created_by`, `note`, `gender`, `title`, `nic`, `address`, `birth_day`, `blood_group`, `allergies`, `special_concern`) VALUES
(1, 2, '0000-00-00 00:00:00', 0, '', 'Female', 'Miss', '867393927V', 'No. 101/ A/4, Malabe road, Kothalawala,', '1986-08-26', 'O+', '', ''),
(2, 6, '0000-00-00 00:00:00', 0, '', 'Male', 'Mr.', 'adjfffjkke', 'No. 101/ A/4, Malabe road, Kothalawala,', '1951-01-21', 'O+', '', ''),
(3, 7, '0000-00-00 00:00:00', 0, '', 'Male', '', '', '', '2000-01-01', 'O+', '', ''),
(4, 8, '0000-00-00 00:00:00', 0, '', 'Male', '', '', '', '2000-01-01', 'O+', 'test', ''),
(5, 9, '0000-00-00 00:00:00', 0, '', 'Male', 'Mr.', '832653500V', 'No. 101/ A/4, Malabe road, Kothalawala,', '1989-09-21', 'O+', '', ''),
(6, 12, '0000-00-00 00:00:00', 0, '', 'Male', '', '', '', '2000-01-01', 'O+', '', ''),
(7, 13, '0000-00-00 00:00:00', 0, '', 'Male', '', '', '', '2000-01-01', 'O+', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_specialty`
--

CREATE TABLE `tbl_specialty` (
  `id` int(11) NOT NULL,
  `specialty` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_specialty`
--

INSERT INTO `tbl_specialty` (`id`, `specialty`) VALUES
(1, 'Pediatrics'),
(2, 'Cardiology'),
(3, 'Orthopaedics'),
(4, 'Obstetrician/gynecologists'),
(5, 'Neurologists'),
(6, 'Allergy and immunology'),
(7, 'good'),
(8, 'bad'),
(9, 'moderate'),
(10, 'ok');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `phone_no` varchar(50) NOT NULL,
  `email` varchar(500) NOT NULL,
  `password` varchar(5000) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `image_path` varchar(500) NOT NULL,
  `pwc_code` varchar(50) NOT NULL,
  `pwc_code_ref` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `allergies` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `name`, `phone_no`, `email`, `password`, `user_type`, `created_on`, `created_by`, `image_path`, `pwc_code`, `pwc_code_ref`, `username`, `allergies`) VALUES
(1, 'System admin', '0761418475', 'admin@gmail.com', '$2a$10$7RHh0ZlrTynJ43A77oMokebmUOGnpbLTm49l7ZQP7pcwmMoK8vpUK', 'user', '0000-00-00 00:00:00', 0, 'documents/userimages/1.png', '', '', 'admin', ''),
(2, 'Amanda Illankoon', '0761418475', 'amanda.illangakoon@gmail.com', '$2a$10$PUl5/s5M22R05MDiUopzBexgZZ4oJWP0gzGaqj4I3KlOYOTOMBt0m', 'patient', '2021-07-19 14:01:26', 0, 'documents/userimages/2.png', '64712', '202112041122', 'amanda', ''),
(3, 'Oshan Illangakoon', '0761418475', 'oshaan@gmail.com', '$2a$10$jZm3LpIPq9iBSp83Vc4VFu49naP0CIFraKLfL.R1yAGInODGe55KC', 'doctor', '2021-07-20 09:15:16', 0, 'documents/userimages/3.png', '', '', 'oshaan', ''),
(4, 'Sankani', '0770455618', 'Sankani@gmail.com', '$2a$10$/sQsVxVgvswxSi5Dk5KHf.OvHR1AtzhiD8IQ4jmhJt/owONbAamla', 'doctor', '2021-08-13 21:13:08', 0, 'Images/user.jpg', '', '', '', ''),
(5, 'Piyumali Herath', '0771423482', 'piyumaliherath92@yahoo.com', '$2a$10$AdyXlJcFvnNT.VjOWyS2FOUWMBCXgoOds0yaOVQGe3mGRkfuIWhfC', 'doctor', '2021-09-10 14:57:14', 0, 'Images/user.jpg', '', '', '', ''),
(6, 'Kamal Illangakoon', '0761418475', 'kamal@gmail.com', '$2a$10$B3.hq61XyxnIU9.IplLlD.i6bipFY0iG1/K4nuZ.Dg95upVImmojW', 'patient', '2021-09-11 07:21:52', 0, 'Images/user.jpg', '', '', '', ''),
(7, 'abc', '0770455618', 'abc@gmail.com', '$2a$10$2rlU5lEHtkiQ5uspFWWc7..5olTpVgvRtNizL4t/MLlagt2Ws1VwG', 'patient', '2021-09-19 21:28:56', 0, 'Images/user.jpg', '', '', '', ''),
(8, 'test ', '0770455618', 'test@gmail.com', '$2a$10$/YlbeduRc4Cw1WwAPFEabeHoJWwjFxgMTKxLoOiKkvPRgxPePgTRK', 'patient', '2021-09-20 08:35:01', 0, 'Images/user.jpg', '', '', '', ''),
(9, 'Harsha Nawarathna', '0770455618', 'harshaatefac@gmail.com', '$2a$10$zK.9.1x1GA5AK4FGMMTBHu0jZvWHfSiFdWcBCV.MzJ7bgXAHddlmu', 'patient', '2021-09-27 09:07:16', 0, 'Images/user.jpg', '', '', '', ''),
(10, 'Piyumali Herath', '0770455618', 'piyumaliherath93@yahoo.com', '$2a$10$3mqxau9yUohWI3xXMPTY.e9bNP7Ad6tSbMcEncvKrur60zqRM7ydC', 'doctor', '2021-09-27 11:34:59', 0, 'Images/user.jpg', '', '', '', ''),
(11, 'malika', '0770455618', 'malika@gmail.com', '$2a$10$snEN2v0bWhbWFzqtcFSMnu9pnnjq22ztJrAK0LuLeK.9zJMbCRdOu', 'doctor', '2021-09-27 11:36:36', 0, 'Images/user.jpg', '', '', '', ''),
(12, 'Abc', '0770455618', 'abc1@gmail.com', '$2a$10$44Q7Knv2.ndnl/PNB2jJA.E/.KJ6hHnPtYAXcO21AE0AMUEuVkvya', 'patient', '2021-09-27 18:50:08', 0, 'Images/user.jpg', '', '', '', ''),
(13, 'Pushpa Kodippiliarachchi', '0761418475', 'def@gmail.com', '$2a$10$7RHh0ZlrTynJ43A77oMokebmUOGnpbLTm49l7ZQP7pcwmMoK8vpUK', 'patient', '2021-12-04 13:34:29', 0, 'Images/user.jpg', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `extra_specialty`
--
ALTER TABLE `extra_specialty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_channelling`
--
ALTER TABLE `tbl_channelling`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_channelling_doc`
--
ALTER TABLE `tbl_channelling_doc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_chat`
--
ALTER TABLE `tbl_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_doctors`
--
ALTER TABLE `tbl_doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_medical_reports`
--
ALTER TABLE `tbl_medical_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_medical_tests`
--
ALTER TABLE `tbl_medical_tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_medicine`
--
ALTER TABLE `tbl_medicine`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_patients`
--
ALTER TABLE `tbl_patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_specialty`
--
ALTER TABLE `tbl_specialty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_channelling`
--
ALTER TABLE `tbl_channelling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tbl_channelling_doc`
--
ALTER TABLE `tbl_channelling_doc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_chat`
--
ALTER TABLE `tbl_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_doctors`
--
ALTER TABLE `tbl_doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_medical_reports`
--
ALTER TABLE `tbl_medical_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_medical_tests`
--
ALTER TABLE `tbl_medical_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_medicine`
--
ALTER TABLE `tbl_medicine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_patients`
--
ALTER TABLE `tbl_patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_specialty`
--
ALTER TABLE `tbl_specialty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
