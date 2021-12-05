-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2021 at 06:17 AM
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
-- Database: `060_medicare`
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
  `doctor_notes` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `birth_day` date NOT NULL DEFAULT '2000-01-01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(6, 'Allergy and immunology');

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
  `image_path` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `name`, `phone_no`, `email`, `password`, `user_type`, `created_on`, `created_by`, `image_path`) VALUES
(1, 'System admin', '0770000000', 'admin@gmail.com', '$2a$10$QzfjIw8R3LvbdQ3L.MjVPe/AW0G0YCiy2fJSj7cm9KBuS3VcZPTCS', 'user', '0000-00-00 00:00:00', 0, 'documents/userimages/6.png');

--
-- Indexes for dumped tables
--

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_channelling_doc`
--
ALTER TABLE `tbl_channelling_doc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_doctors`
--
ALTER TABLE `tbl_doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_medical_reports`
--
ALTER TABLE `tbl_medical_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_patients`
--
ALTER TABLE `tbl_patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_specialty`
--
ALTER TABLE `tbl_specialty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
