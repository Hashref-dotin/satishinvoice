using System;
using System.Data;
using System.Configuration;
using System.Collections;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;

public partial class frmDomainMenuMaster : System.Web.UI.Page
{
    commonclass clscon = new commonclass();
    protected void Page_Load(object sender, EventArgs e)
    {
        if (Page.IsPostBack == false)
        {

            clscon.SetDatainDDL(ddldomain,"select * from tbtcdomainmaster order by domainname", "domainname", "domainid");
            BindGrid();
        }
    }
    private void maxMenuOrderTAB()
    {
        if (ddldomain.SelectedIndex >= 1)
        {
            txttaborder.Text = Convert.ToString(clscon.Return_Int("select min(orderno) from orders where orderno not in(select orderno from tbdomaintabmaster where domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + ")"));
        }
        else
        {
            txttaborder.Text = "";
        }
    }
    private void maxMenuOrder()
    {
        if (ddldomain.SelectedIndex >= 1)
        {
            if (ddltabmenu.SelectedIndex >= 1)
            {
                txtordermain.Text = Convert.ToString(clscon.Return_Int("select min(orderno) from orders where orderno not in(select orderno from tbdomainmenumaster where level=1 and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + " and tabid=" + Convert.ToInt32(ddltabmenu.SelectedValue) + ")"));
            }
        }
        else
        {
            txtordermain.Text = "";
        }
    }
    private void maxSubMenuOrder()
    
    {
        if (ddlmenu.SelectedIndex >= 1)
        {
            txtordersub.Text = Convert.ToString(clscon.Return_Int("select min(orderno) from orders where orderno not in(select orderno from tbdomainmenumaster where level=2 and parentmenuid=" + Convert.ToInt32(ddlmenu.SelectedValue) + " and domainid="+ Convert.ToInt32(ddldomain.SelectedValue) + ")"));
        }
        else
        {
            txtordersub.Text = "";
        }
    }
    private void BindGrid()
    {
        // Bind Zero
        DataSet ds = new DataSet();
        if (ddldomain.SelectedIndex >= 1)
        {
            clscon.Return_DS(ds, "select b.domainname,b.domainid  from tbtcdomainmaster b where b.domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "  order by b.domainname");
        }
        else
        {
            clscon.Return_DS(ds, "select b.domainname,b.domainid  from tbtcdomainmaster b  order by b.domainname");
        }
       
        grvExisting.DataSource = ds;
        grvExisting.DataBind();
        int i = 0;
        for (i = 0; i <= grvExisting.Rows.Count - 1; i++)
        {
            //BIND FIRST GRID
            GridView gvinfirst = new GridView();
            gvinfirst = ((GridView)(grvExisting.Rows[i].FindControl("gvinfirst")));
            try
            {
                int domainid = Convert.ToInt32(grvExisting.DataKeys[i].Value);
                
                DataSet dsinfirst = new DataSet();
                clscon.Return_DS(dsinfirst, "select * from tbdomaintabmaster where  domainid=" + domainid + " order by orderno");
                gvinfirst.DataSource = dsinfirst;
                gvinfirst.DataBind();
                //BIND SECOND GRID
                int k = 0;
                for (k = 0; k <= gvinfirst.Rows.Count - 1; k++)
                {
                    GridView gvinsecond = new GridView();
                    gvinsecond = ((GridView)(gvinfirst.Rows[k].FindControl("gvinsecond")));
                    try
                    {
                        int tabid = Convert.ToInt32(gvinfirst.DataKeys[k].Value);

                        DataSet dsinsecond = new DataSet();
                        clscon.Return_DS(dsinsecond, "select * from tbdomainmenumaster where  domainid=" + domainid + " and tabid=" + tabid + " and level=1 order by orderno");
                        gvinsecond.DataSource = dsinsecond;
                        gvinsecond.DataBind();
                        //BIND tHIRD GRID
                   
                        int l = 0;
                        for (l = 0; l <= gvinsecond.Rows.Count - 1; l++)
                        {
                            GridView gvinthird = new GridView();
                            gvinthird = ((GridView)(gvinsecond.Rows[l].FindControl("gvinthird")));
                            try
                            {
                                int menuid = Convert.ToInt32(gvinsecond.DataKeys[l].Value);
                                int parentmenuid = menuid;
                                DataSet dsinthird = new DataSet();
                                clscon.Return_DS(dsinthird, "select * from tbdomainmenumaster where  level=2 and parentmenuid=" + menuid + " order by orderno");
                                gvinthird.DataSource = dsinthird;
                                gvinthird.DataBind();


                                //

                            }
                            catch
                            {

                            }
                        }
                        //

                        //

                    }
                    catch
                    {

                    }
                }
                //

            }
            catch
            {

            }
            //
            
        }
        
    }
    protected void btnsavemainmenu_Click(object sender, EventArgs e)
    {
        if (btnsavemainmenu.Text == "Save")
        {
            if (ddldomain.SelectedIndex >= 1)
            {
            }
            else
            {
                Alert("Select Domain");
                ModalPopupExtendervip.Show();
                return;
            }
            if (ddltabmenu.SelectedIndex >= 1)
            {
            }
            else
            {
                Alert("Select Tab");
                ModalPopupExtendervip.Show();
                return;
            }
            if (clscon.check("select * from tbdomainmenumaster where level=1 and menuname='" + txtmenuitem.Text + "' and tabid=" + Convert.ToInt32(ddltabmenu.SelectedValue) + " and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "") == true)
            {
                Alert("Menu Item Already Exist,Please Try Another Name");
                ModalPopupExtendervip.Show();
                return;
            }
            if (clscon.check("select * from tbdomainmenumaster where level=1 and orderno='" + txtordermain.Text + "' and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + " and tabid=" + Convert.ToInt32(ddltabmenu.SelectedValue) + "") == true)
            {
                Alert("Order No. Already Exists,Please Try Another");
                ModalPopupExtendervip.Show();
                return;
            }
            clscon.Execqry("insert into tbdomainmenumaster(menuname,parentmenuid,domainid,level,orderno,status,tabid) values('" + txtmenuitem.Text + "','0'," + Convert.ToInt32(ddldomain.SelectedValue) + ",'1','" + txtordermain.Text + "','Active'," + Convert.ToInt32(ddltabmenu.SelectedValue) + ")");
            Rset();
            Alert("Menu Item Saved Successfully");

        }
        else
        {
            int menuid = Convert.ToInt32(ViewState["menuid"]);
                if (ddldomain.SelectedIndex >= 1)
                {
                }
                else
                {
                    Alert("Select Domain");
                    ModalPopupExtendervip.Show();
                    return;
                }
                if (ddltabmenu.SelectedIndex >= 1)
                {
                }
                else
                {
                    Alert("Select Tab");
                    ModalPopupExtendervip.Show();
                    return;
                }
                int tabid = Convert.ToInt32(ddltabmenu.SelectedValue);
                if (clscon.check("select * from tbdomainmenumaster where level=1 and menuname='" + txtmenuitem.Text + "' and menuid!=" + menuid + "  and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + " and tabid=" + tabid + "") == true)
                {
                    Alert("Menu Item Already Exist,Please Try Another Name");
                    ModalPopupExtendervip.Show();
                    return;
                }
                if (clscon.check("select * from tbdomainmenumaster where level=1 and orderno='" + txtordermain.Text + "' and menuid!=" + menuid + "  and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + " and tabid=" + tabid + "") == true)
                {
                    Alert("Order No. Already Assigned To Another Menu,Please Try Different Order");
                    ModalPopupExtendervip.Show();
                    return;
                }
                clscon.Execqry("update tbdomainmenumaster set tabid=" + tabid + ",menuname='" + txtmenuitem.Text + "',parentmenuid='0',domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + ",level='1',orderno='" + txtordermain.Text + "' where menuid=" + menuid + "");
                Rset();
                Alert("Updated Successfully");

            
        }

    }
    private void Rset()
    {
      //  ddldomain.SelectedIndex = 0;
        ddldomain.SelectedIndex = 0;
        ddltabmenu.Items.Clear();
        ddltabsubmenu.Items.Clear();
        txtordermain.Text = "";
        txtmenuitem.Text = "";
        txtordersub.Text = "";
        txtsubmenu.Text = "";
        txttabitem.Text = "";
        txttaborder.Text = "";
        btntabsave.Text = "Save";
        btndelete.Visible = false;
        //ddlmenu.SelectedIndex = 0;
        btnsavemainmenu.Text = "Save";
        btnsavesubmenu.Text = "Save";
        btntabsave.Text = "Save";
        BindGrid();
        //if (ddldomain.SelectedIndex >= 1)
        //{
        //    maxMenuOrder();
        //    ddlmenu.Items.Clear();
        //    clscon.SetDatainDDL(ddlmenu, "select * from tbdomainmenumaster where level=1 and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "  order by orderno", "menuname", "menuid");
        //    BindGrid();
        //}
        //else
        //{
        //    ddlmenu.Items.Clear();
        //    grvExisting.DataSource = null;
        //    grvExisting.DataBind();
        //}
        pnlmain.Visible = false;
        
        pnlmenu.Visible = false;
        pnltab.Visible = false;
        pnlsubmenu.Visible = false;
        ModalPopupExtendervip.Show();
     
    }
    private void Alert(string str)
    {
        Response.Write("<script>window.alert('"+str+"')</script>");
    }
    protected void btnreset_Click(object sender, EventArgs e)
    {
        Rset();
    }
    protected void btnresetsubmenu_Click(object sender, EventArgs e)
    {
        Rset();
    }
    protected void btnsavesubmenu_Click(object sender, EventArgs e)
    {
        if (btnsavesubmenu.Text == "Save")
        {
            if (ddldomain.SelectedIndex >= 1)
            {
            }
            else
            {
                Alert("Select Domain");
                ModalPopupExtendervip.Show();
                return;
            }
            if (ddlmenu.SelectedIndex >= 1)
            {
            }
            else
            {
                Alert("Select Menu");
                ModalPopupExtendervip.Show();
                return;
            }
            if (ddltabsubmenu.SelectedIndex >= 1)
            {
            }
            else
            {
                Alert("Select Tab");
                ModalPopupExtendervip.Show();
                return;
            }
            int tabid = Convert.ToInt32(ddltabsubmenu.SelectedValue);
            if (clscon.check("select * from tbdomainmenumaster where level=2 and menuname='" + txtsubmenu.Text + "' and parentmenuid=" + Convert.ToInt32(ddlmenu.SelectedValue) + "  and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + " and tabid=" + tabid + "") == true)
            {
                Alert("Menu Item Already Exist,Please Try Another Name");
                ModalPopupExtendervip.Show();
                return;
            }
            if (clscon.check("select * from tbdomainmenumaster where level=2 and orderno='" + txtordersub.Text + "' and parentmenuid=" + Convert.ToInt32(ddlmenu.SelectedValue) + "  and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "  and tabid=" + tabid + "") == true)
            {
                Alert("Order No. Already Assigned,Please Try Another Order No.");
                ModalPopupExtendervip.Show();
                return;
            }
            clscon.Execqry("insert into tbdomainmenumaster(menuname,parentmenuid,domainid,level,orderno,status,tabid) values('" + txtsubmenu.Text + "',"+ Convert.ToInt32(ddlmenu.SelectedValue) +"," + Convert.ToInt32(ddldomain.SelectedValue) + ",'2','" + txtordersub.Text + "','Active'," + tabid + ")");
            Rset();
            Alert("Menu Item Saved Successfully");

        }
        else
        {
            int menuid = Convert.ToInt32(ViewState["menuid"]);
            if (ddldomain.SelectedIndex >= 1)
            {
            }
            else
            {
                Alert("Select Domain");
                ModalPopupExtendervip.Show();
                return;
            }
            if (ddlmenu.SelectedIndex >= 1)
            {
            }
            else
            {
                Alert("Select Menu");
                ModalPopupExtendervip.Show();
                return;
            }
            if (ddltabsubmenu.SelectedIndex >= 1)
            {
            }
            else
            {
                Alert("Select Tab");
                ModalPopupExtendervip.Show();
                return;
            }
            int tabid = Convert.ToInt32(ddltabsubmenu.SelectedValue);
            if (clscon.check("select * from tbdomainmenumaster where level=2 and menuname='" + txtsubmenu.Text + "' and parentmenuid=" + Convert.ToInt32(ddlmenu.SelectedValue) + " and menuid!=" + menuid + "  and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + " and tabid=" + tabid + "") == true)
            {
                Alert("Menu Item Already Exist,Please Try Another Name");
                ModalPopupExtendervip.Show();
                return;
            }
            if (clscon.check("select * from tbdomainmenumaster where level=2 and orderno='" + txtordersub.Text + "' and parentmenuid=" + Convert.ToInt32(ddlmenu.SelectedValue) + " and menuid!=" + menuid + "  and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "  and tabid=" + tabid + "") == true)
            {
                Alert("Order No. Already Assigned,Please Try Another");
                ModalPopupExtendervip.Show();
                return;
            }
            clscon.Execqry("update tbdomainmenumaster set tabid=" + tabid + ",menuname='" + txtsubmenu.Text + "',parentmenuid='"+ Convert.ToInt32(ddlmenu.SelectedValue)+ "',domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + ",level='2',orderno='" + txtordersub.Text + "' where menuid=" + menuid + "");
            Rset();
            Alert("Updated Successfully");


        }

    }
    protected void ddldomain_SelectedIndexChanged(object sender, EventArgs e)
    {
        
        if (ddldomain.SelectedIndex >= 1)
        {
            clscon.SetDatainDDL(ddltabmenu, "select * from tbdomaintabmaster where domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "  order by orderno", "tabname", "tabid");
            clscon.SetDatainDDL(ddltabsubmenu, "select * from tbdomaintabmaster where domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "  order by orderno", "tabname", "tabid");
            //clscon.SetDatainDDL(ddlmenu, "select * from tbdomainmenumaster where level=1 and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "  order by orderno", "menuname", "menuid");
            maxMenuOrderTAB();
            BindGrid();
        }
        else
        {
            Rset();
        }
        ModalPopupExtendervip.Show();
    }
    protected void ddlmenu_SelectedIndexChanged(object sender, EventArgs e)
    {
        ModalPopupExtendervip.Show();
        maxSubMenuOrder();
        BindGrid();
    }
    protected void grvExisting_SelectedIndexChanging(object sender, GridViewSelectEventArgs e)
    {
        int menuid = Convert.ToInt32(grvExisting.DataKeys[e.NewSelectedIndex].Value);
        DataSet ds=new DataSet ();
        clscon.Return_DS(ds,"select * from tbdomainmenumaster where menuid=" + menuid + "");
        int domainid = Convert.ToInt32(ds.Tables[0].Rows[0]["domainid"]);
        ddldomain.SelectedValue = domainid.ToString();
        clscon.SetDatainDDL(ddlmenu, "select * from tbdomainmenumaster where level=1 and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "  order by orderno", "menuname", "menuid");
        maxMenuOrder();
      
        int parentmenuid =Convert.ToInt32( ds.Tables[0].Rows[0]["parentmenuid"]);

        if (parentmenuid == 0)
        {
            pnlmain.Visible = true;
            ModalPopupExtendervip.Show();
            pnlmenu.Visible = true;
            pnlsubmenu.Visible = false;
            ddlmenu.SelectedIndex = 0;
            txtsubmenu.Text = "";
            txtordersub.Text = "";
            btnsavesubmenu.Text = "Save";

            txtmenuitem.Text = ds.Tables[0].Rows[0]["menuname"].ToString();
            txtordermain.Text = ds.Tables[0].Rows[0]["orderno"].ToString();
            btnsavemainmenu.Text = "Update";
            ViewState["menuid"] = menuid;
        }
        else
        {
            pnlmain.Visible = true;
            ModalPopupExtendervip.Show();
            pnlsubmenu.Visible = true;
            pnlmenu.Visible = false;
            txtmenuitem.Text = "";
            txtordermain.Text = "";
            btnsavemainmenu.Text = "Save";
            ddlmenu.SelectedValue = parentmenuid.ToString();
            txtsubmenu.Text = ds.Tables[0].Rows[0]["menuname"].ToString();
            txtordersub.Text = ds.Tables[0].Rows[0]["orderno"].ToString();
            btnsavesubmenu.Text = "Update";
            ViewState["menuid"] = menuid;
        }
    }
    protected void grvExisting_RowCommand(object sender, GridViewCommandEventArgs e)
    {
        if (e.CommandName == "status")
        {
            int tabid = Convert.ToInt32(e.CommandArgument);
        string status= clscon.Return_string("select status from tbdomaintabmaster where tabid=" + tabid + "");
        if (status == "Active")
        {
            clscon.Execqry("update tbdomaintabmaster set status='Inactive' where tabid=" + tabid + "");
        }
        else
        {
            clscon.Execqry("update tbdomaintabmaster set status='Active' where tabid=" + tabid + "");
        }
        BindGrid();
        }
        else if (e.CommandName == "tab")
        {
            Rset();
            pnlmain.Visible = true;
            pnltab.Visible = true;
            ModalPopupExtendervip.Show();
            int tabid = Convert.ToInt32(e.CommandArgument);
            ViewState["tabid"] = tabid;
            DataSet ds = new DataSet();
            btntabsave.Text ="Update";

            clscon.Return_DS(ds,"select * from tbdomaintabmaster where tabid=" + tabid + "");
            if (ds.Tables[0].Rows.Count >= 1)
            {
                ddldomain.SelectedValue = ds.Tables[0].Rows[0]["domainid"].ToString();
                ddltabmenu.SelectedValue = ds.Tables[0].Rows[0]["tabid"].ToString();
                txttabitem.Text = ds.Tables[0].Rows[0]["tabname"].ToString();
                txttaborder.Text = ds.Tables[0].Rows[0]["orderno"].ToString();
                btndelete.Visible = true;
            }
            
            ModalPopupExtendervip.Show();
        }
        if (e.CommandArgument == "deletetab")
        {
            int tabid = Convert.ToInt32(e.CommandArgument);
            if (clscon.check("select * from tbdomainmenumaster where tabid=" + tabid + "") == true)
            {
                Alert("This Tab Contains Some Items,So Unable To Delete");
                return;
            }
            else
            {
                clscon.Execqry("delete from tbdomaintabmaster where tabid=" + tabid + "");

            }
            Alert("Deleted Successfully");
            BindGrid();
        }
    }
    protected void grvExisting_RowDeleting(object sender, GridViewDeleteEventArgs e)
    {
        int menuid=Convert.ToInt32(grvExisting.DataKeys[e.RowIndex].Value);
        if(clscon.check("select * from tbdomaintestallocation where menuid=" + menuid + "")==true)
        {
            Alert("Some Tests Are Allocated To This Menu!Unable To Delete");
            return ;
        }
        clscon.Execqry("delete from tbdomainmenumaster where menuid=" + menuid + "");
        BindGrid();
    }
    protected void lnkaddmenu_Click(object sender, EventArgs e)
    {
        Rset();

        pnlmain.Visible = true;
        pnlmenu.Visible = true;
        ModalPopupExtendervip.Show();
    }
    protected void lnkaddsubmenu_Click(object sender, EventArgs e)
    {
        Rset();
        pnlmain.Visible = true;
        pnlsubmenu.Visible = true;
        ModalPopupExtendervip.Show();
    }
    
    protected void gvin_RowCommand(object sender, GridViewCommandEventArgs e)
    {
        if (e.CommandName == "sel")
        {
            int menuid = Convert.ToInt32(e.CommandArgument);
            DataSet ds = new DataSet();
            clscon.Return_DS(ds, "select * from tbdomainmenumaster where menuid=" + menuid + "");
            int domainid = Convert.ToInt32(ds.Tables[0].Rows[0]["domainid"]);
            ddldomain.SelectedValue = domainid.ToString();
            clscon.SetDatainDDL(ddltabsubmenu, "select * from tbdomaintabmaster where domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "  order by orderno", "tabname", "tabid");
            ddltabsubmenu.SelectedValue = ds.Tables[0].Rows[0]["tabid"].ToString();
            clscon.SetDatainDDL(ddlmenu, "select * from tbdomainmenumaster where level=1 and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + " and tabid=" + Convert.ToInt32(ddltabsubmenu.SelectedValue) + "  order by orderno", "menuname", "menuid");
          //  clscon.SetDatainDDL(ddlmenu, "select * from tbdomainmenumaster where level=1 and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "  order by orderno", "menuname", "menuid");
            maxMenuOrder();
            int parentmenuid = Convert.ToInt32(ds.Tables[0].Rows[0]["parentmenuid"]);
            if (parentmenuid == 0)
            {
                pnlmain.Visible = true;
                pnlmenu.Visible = true;
                ModalPopupExtendervip.Show();
                pnlsubmenu.Visible = false;
                ddlmenu.SelectedIndex = 0;
                txtsubmenu.Text = "";
                txtordersub.Text = "";
                btnsavesubmenu.Text = "Save";

                txtmenuitem.Text = ds.Tables[0].Rows[0]["menuname"].ToString();
                txtordermain.Text = ds.Tables[0].Rows[0]["orderno"].ToString();
                btnsavemainmenu.Text = "Update";
                ViewState["menuid"] = menuid;
            }
            else
            {
                pnlmain.Visible = true;
                ModalPopupExtendervip.Show();
                pnlsubmenu.Visible = true;
                pnlmenu.Visible = false;
                txtmenuitem.Text = "";
                txtordermain.Text = "";
                btnsavemainmenu.Text = "Save";
                ddlmenu.SelectedValue = parentmenuid.ToString();
                txtsubmenu.Text = ds.Tables[0].Rows[0]["menuname"].ToString();
                txtordersub.Text = ds.Tables[0].Rows[0]["orderno"].ToString();
                btnsavesubmenu.Text = "Update";
                ViewState["menuid"] = menuid;
            }
        }
        else if (e.CommandName == "del")
        {
            int menuid = Convert.ToInt32(e.CommandArgument);
            if (clscon.check("select * from tbdomaintestallocation where menuid=" + menuid + "") == true)
            {
                Alert("Some Tests Are Allocated To This Menu!Unable To Delete");
                return;
            }
            clscon.Execqry("delete from tbdomainmenumaster where menuid=" + menuid + "");
            BindGrid();
        }
        else if (e.CommandName == "status")
        {
            int menuid = Convert.ToInt32(e.CommandArgument);
            string status = clscon.Return_string("select status from tbdomainmenumaster where menuid=" + menuid + "");
            if (status == "Active")
            {
                clscon.Execqry("update tbdomainmenumaster set status='Inactive' where menuid=" + menuid + "");
            }
            else
            {
                clscon.Execqry("update tbdomainmenumaster set status='Active' where menuid=" + menuid + "");
            }
            BindGrid();
        }
    }
    protected void lnktab_Click(object sender, EventArgs e)
    {
        Rset();

        pnlmain.Visible = true;
        pnltab.Visible = true;
        ModalPopupExtendervip.Show();
    }
    protected void btntabsave_Click(object sender, EventArgs e)
    {
        if (btntabsave.Text == "Save")
        {
            if (ddldomain.SelectedIndex >= 1)
            {
            }
            else
            {
                Alert("Select Domain");
                ModalPopupExtendervip.Show();
                return;
            }
            
            if (clscon.check("select * from tbdomaintabmaster where tabname='" + txttabitem.Text + "' and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "") == true)
            {
                Alert("Tab Item Already Exist,Please Try Another Name");
                ModalPopupExtendervip.Show();
                return;
            }
            if (clscon.check("select * from tbdomaintabmaster where  orderno='" + txttaborder.Text + "' and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "") == true)
            {
                Alert("Order No. Already Exists,Please Try Another");
                ModalPopupExtendervip.Show();
                return;
            }
            clscon.Execqry("insert into tbdomaintabmaster(tabname,domainid,orderno,status) values('" + txttabitem.Text + "'," + Convert.ToInt32(ddldomain.SelectedValue) + ",'" + txttaborder.Text + "','Active')");
            Rset();
            Alert("Tab Item Saved Successfully");

        }
        else
        {
            int tabid = Convert.ToInt32(ViewState["tabid"]);
            if (ddldomain.SelectedIndex >= 1)
            {
            }
            else
            {
                Alert("Select Domain");
                ModalPopupExtendervip.Show();
                return;
            }
            if (clscon.check("select * from tbdomaintabmaster where tabname='" + txttabitem.Text + "' and tabid!=" + tabid + " and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "") == true)
            {
                Alert("Tab Item Already Exist,Please Try Another Name");
                ModalPopupExtendervip.Show();
                return;
            }
            if (clscon.check("select * from tbdomaintabmaster where  orderno='" + txttaborder.Text + "' and tabid!=" + tabid + " and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "") == true)
            {
                Alert("Order No. Already Exists,Please Try Another");
                ModalPopupExtendervip.Show();
                return;
            }
            
            clscon.Execqry("update tbdomaintabmaster set tabname='" + txttabitem.Text + "',domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + ",orderno='" + txttaborder.Text + "' where tabid=" + tabid + "");
            Rset();
            Alert("Updated Successfully");


        }

    }

    protected void ddltabmenu_SelectedIndexChanged(object sender, EventArgs e)
    {
        maxMenuOrder();
        ModalPopupExtendervip.Show();
    }
    protected void ddltabsubmenu_SelectedIndexChanged(object sender, EventArgs e)
    {
        //ddlmenu
        if (ddldomain.SelectedIndex <= 0)
        {
            ddlmenu.Items.Clear();
            ModalPopupExtendervip.Show();
            return;
        }

        if (ddltabsubmenu.SelectedIndex >= 1)
        {
            clscon.SetDatainDDL(ddlmenu, "select * from tbdomainmenumaster where level=1 and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + " and tabid=" + Convert.ToInt32(ddltabsubmenu.SelectedValue) + "  order by orderno", "menuname", "menuid");
        }
        else
        {
            ddlmenu.Items.Clear();
        }
        ModalPopupExtendervip.Show();
    }
  
    protected void btntabreset_Click(object sender, EventArgs e)
    {
        Rset();
    }
    protected void btndelete_Click(object sender, EventArgs e)
    {
        int tabid = Convert.ToInt32(ViewState["tabid"]);
        if (clscon.check("select * from tbdomainmenumaster where tabid=" + tabid + "") == true)
        {
            Alert("This Tab Contains Some Items,So Unable To Delete");
            return;
        }
        else
        {
            clscon.Execqry("delete from tbdomaintabmaster where tabid=" + tabid + "");

        }
        Rset();
        Alert("Deleted Successfully");
        BindGrid();
    }
    protected void gvinsecond_RowDeleting(object sender, GridViewDeleteEventArgs e)
    {

        int menuid = Convert.ToInt32(grvExisting.DataKeys[e.RowIndex].Value);
        if (clscon.check("select * from tbdomaintestallocation where menuid=" + menuid + "") == true)
        {
            Alert("Some Tests Are Allocated To This Menu!Unable To Delete");
            return;
        }
        clscon.Execqry("delete from tbdomainmenumaster where menuid=" + menuid + "");
        BindGrid();
    }
    protected void gvinsecond_RowCommand(object sender, GridViewCommandEventArgs e)
    {
        if (e.CommandName == "two")
        {
            int menuid = Convert.ToInt32(e.CommandArgument);
            if (clscon.check("select * from tbdomaintestallocation where menuid=" + menuid + "") == true)
            {
                Alert("Some Tests Are Allocated To This Menu!Unable To Delete");
                return;
            }
            clscon.Execqry("delete from tbdomainmenumaster where menuid=" + menuid + "");
            BindGrid();
        }
        else if (e.CommandName == "status")
        {
            int menuid = Convert.ToInt32(e.CommandArgument);
            string status = clscon.Return_string("select status from tbdomainmenumaster where menuid=" + menuid + "");
            if (status == "Active")
            {
                clscon.Execqry("update tbdomainmenumaster set status='Inactive' where menuid=" + menuid + "");
            }
            else
            {
                clscon.Execqry("update tbdomainmenumaster set status='Active' where menuid=" + menuid + "");
            }
            BindGrid();
        }
        else if (e.CommandName == "one")
        {
            int menuid = Convert.ToInt32(e.CommandArgument);
            DataSet ds = new DataSet();
            clscon.Return_DS(ds, "select * from tbdomainmenumaster where menuid=" + menuid + "");
            int domainid = Convert.ToInt32(ds.Tables[0].Rows[0]["domainid"]);

            ddldomain.SelectedValue = domainid.ToString();
            clscon.SetDatainDDL(ddltabmenu, "select * from tbdomaintabmaster where domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + "  order by orderno", "tabname", "tabid");
            ddltabmenu.SelectedValue = ds.Tables[0].Rows[0]["tabid"].ToString();

            // clscon.SetDatainDDL(ddlmenu, "select * from tbdomainmenumaster where level=1 and domainid=" + Convert.ToInt32(ddldomain.SelectedValue) + " and tabid=" + Convert.ToInt32(ddltabsubmenu.SelectedValue) + "  order by orderno", "menuname", "menuid");
            //maxMenuOrder();

            int parentmenuid = Convert.ToInt32(ds.Tables[0].Rows[0]["parentmenuid"]);

            if (parentmenuid == 0)
            {
                pnlmain.Visible = true;
                ModalPopupExtendervip.Show();
                pnlmenu.Visible = true;
                pnlsubmenu.Visible = false;
                //ddltabmenu
                ddlmenu.Items.Clear();
                txtsubmenu.Text = "";
                txtordersub.Text = "";
                btnsavesubmenu.Text = "Save";

                txtmenuitem.Text = ds.Tables[0].Rows[0]["menuname"].ToString();
                txtordermain.Text = ds.Tables[0].Rows[0]["orderno"].ToString();
                btnsavemainmenu.Text = "Update";
                ViewState["menuid"] = menuid;
            }
            else
            {
                pnlmain.Visible = true;
                ModalPopupExtendervip.Show();
                pnlsubmenu.Visible = true;
                pnlmenu.Visible = false;
                txtmenuitem.Text = "";
                txtordermain.Text = "";
                btnsavemainmenu.Text = "Save";
                ddlmenu.SelectedValue = parentmenuid.ToString();
                txtsubmenu.Text = ds.Tables[0].Rows[0]["menuname"].ToString();
                txtordersub.Text = ds.Tables[0].Rows[0]["orderno"].ToString();
                btnsavesubmenu.Text = "Update";
                ViewState["menuid"] = menuid;
            }
        }
    }
}
