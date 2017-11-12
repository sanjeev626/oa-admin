<script language="javascript">
      function addRow(dataTable)
      {
        	var table=document.getElementById(dataTable);
        	var rowCount=table.rows.length;
        	//document.write(rowCount);
        	var row=table.insertRow(rowCount);

        	var cell1=row.insertCell(0);
        	//document.write(cell1);
        	var element1=document.createElement("input");
        	element1.type="text";
        	element1.name="item_description[]";
        	cell1.appendChild(element1);

        	//var cell2=row.insertCell(1);
        	//document.write(cell2);
        	//cell2.innerHTML=rowCount+1;
        	var cell2=row.insertCell(1);
        	var element2=document.createElement("input");
        	element2.type="text";
        	element2.name="pack[]";
        	cell2.appendChild(element2);

        	var cell3=row.insertCell(1);
        	var element3=document.createElement("input");
        	element3.type="text";
        	element3.name="batch_number[]";
        	cell3.appendChild(element2);

        	var cell4=row.insertCell(1);
        	var element4=document.createElement("input");
        	element4.type="date";
        	element4.name="expiry_date[]";
        	cell4.appendChild(element2);

        	var cell5=row.insertCell(1);
        	var element5=document.createElement("input");
        	element5.type="text";
        	element5.name="quantity[]";
        	cell5.appendChild(element2);

        	var cell6=row.insertCell(1);
        	var element6=document.createElement("input");
        	element6.type="text";
        	element6.name="deal[]";
        	cell6.appendChild(element2);

        	var cell7=row.insertCell(1);
        	var element7=document.createElement("input");
        	element7.type="text";
        	element7.name="rate[]";
        	cell7.appendChild(element2);
        }

        function deleteRow(tableID){
        	try{
        		var table=document.getElementById(tableID);
        		var rowCount=table.rows.length;
        		for(var i=0;i<rowCount;i++)
        			{
        				var row=table.rows[i];
        				var chkbox=row.cells[0].childNodes[0];
        				if(null!=chkbox&&true==chkbox.checked)
        					{
        						table.deleteRow(i);
        						rowCount--;i--;
        					}
        				}
        			}catch(e)
        			{
        				alert(e);
        			}
        		}
</script>
