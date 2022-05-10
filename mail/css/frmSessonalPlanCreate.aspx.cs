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
using MySql.Data.MySqlClient;
public partial class frmModuleCreateLecturesUnderSubject : System.Web.UI.Page
{
    commonclass clscon = new commonclass();
    protected void Page_Load(object sender, EventArgs e)
    {
        if (Page.IsPostBack == false)
        {

            BindLecturesNo();
            BindCourse();
            
        }
    }
    private void BindCourse()
    {
        ClearItems(lstcourse);
        SetDatainLISTvip(lstcourse, "select distinct concat(a.coursename,'-',a.courserep) as coursecmb,a.courseid from tbcoursemaster a,tb_subjectworkshopcourses b where a.courseid=b.courseid order by a.coursename", "coursecmb", "courseid");
    }
    private void BindLecturesNo()
    {
        int i = 0;
        ddlnooflectures.Items.Clear();
        for (i = 0; i <= 100; i++)
        {
            ListItem lst = new ListItem();
            if (i == 0)
            {

                lst.Text = "--";
                lst.Value = "--";

            }
            else
            {
                lst.Text = i.ToString();
                lst.Value = i.ToString();
            }

            ddlnooflectures.Items.Add(lst);
        }
            
    }

    private DataTable ModuleTempCreateColumn()
    {
        DataTable myDataTable = new DataTable();

        DataColumn myDataColumn;

        myDataColumn = new DataColumn();
        myDataColumn.DataType = Type.GetType("System.String");
        myDataColumn.ColumnName = "spno";
        myDataTable.Columns.Add(myDataColumn);

        

        DataColumn myDataColumn2;

        myDataColumn2 = new DataColumn();
        myDataColumn2.DataType = Type.GetType("System.String");
        myDataColumn2.ColumnName = "description";
        myDataTable.Columns.Add(myDataColumn2);

        DataColumn myDataColumn3;

        myDataColumn3 = new DataColumn();
        myDataColumn3.DataType = Type.GetType("System.String");
        myDataColumn3.ColumnName = "hw";
        myDataTable.Columns.Add(myDataColumn3);

        
        DataColumn myDataColumn5;

        myDataColumn5 = new DataColumn();
        myDataColumn5.DataType = Type.GetType("System.Int32");
        myDataColumn5.ColumnName = "spchildid";
        myDataTable.Columns.Add(myDataColumn5);
        DataColumn myDataColumn6;

        myDataColumn6 = new DataColumn();
        myDataColumn6.DataType = Type.GetType("System.String");
        myDataColumn6.ColumnName = "issubjectworkshop";
        myDataTable.Columns.Add(myDataColumn6);

        DataColumn myDataColumn8;
        myDataColumn8 = new DataColumn();
        myDataColumn8.DataType = Type.GetType("System.String");
        myDataColumn8.ColumnName = "swid";
        myDataTable.Columns.Add(myDataColumn8);


        return myDataTable;
    }
    private void ModuleTempBind(DataTable myTable)
    {

        int i;
        int status = 0;
        int p = Convert.ToInt32(ddlnooflectures.SelectedValue);
        for (i = 0; i <= p - 1; i++)
        {
            DataRow row;

            row = myTable.NewRow();
            if (status == 0)
            {
                row["spno"] = Convert.ToString(i + 1);

                row["description"] = "";
                row["hw"] = "";
                
                row["spchildid"] = "0";
                row["issubjectworkshop"] = "";
                row["swid"] = "";

            }



            myTable.Rows.Add(row);

        }
        DataSet ds = new DataSet();
        ds.Tables.Add(myTable);
        gvlec.DataSource = ds;
        gvlec.DataBind();
        
        ViewState["DTLECTURE"] = ds;
    }
    private void MaintainDropDowns()
    {
        DataSet ds = (DataSet)(ViewState["DTLECTURE"]);
        if (ds.Tables[0].Rows.Count == gvlec.Rows.Count)
        {
            int i = 0;
            for (i = 0; i <= ds.Tables[0].Rows.Count - 1; i++)
            {
                ds.Tables[0].Rows[i]["swid"] = Convert.ToString(i + 1);
                DropDownList ddlissubjectworkshop = new DropDownList();
                DropDownList ddlswtitle = new DropDownList();
                ddlissubjectworkshop = ((DropDownList)(gvlec.Rows[i].FindControl("ddlissubjectworkshop")));
                ddlswtitle = ((DropDownList)(gvlec.Rows[i].FindControl("ddlswtitle")));
                if (ddlissubjectworkshop.SelectedIndex >=1)
                {
                    ds.Tables[0].Rows[i]["issubjectworkshop"] = ddlissubjectworkshop.SelectedValue.ToString();

                    if (ddlswtitle.SelectedIndex>=1)
                    {
                        ds.Tables[0].Rows[i]["swid"] = ddlswtitle.SelectedValue;
                    }
                }
            }
        }
        ds.AcceptChanges();
        ViewState["DTLECTURE"] = ds;

    }
    private void ReBindDropDowns()
    {
        DataSet ds = (DataSet)(ViewState["DTLECTURE"]);
        if (ds.Tables[0].Rows.Count == gvlec.Rows.Count)
        {
            int i = 0;
            for (i = 0; i <= ds.Tables[0].Rows.Count - 1; i++)
            {
                DropDownList ddlissubjectworkshop = new DropDownList();
                DropDownList ddlswtitle = new DropDownList();
                ddlissubjectworkshop = ((DropDownList)(gvlec.Rows[i].FindControl("ddlissubjectworkshop")));
                ddlswtitle = ((DropDownList)(gvlec.Rows[i].FindControl("ddlswtitle")));
                if (ds.Tables[0].Rows[i]["issubjectworkshop"].ToString() != "")
                {
                    ddlissubjectworkshop.SelectedValue = ds.Tables[0].Rows[i]["issubjectworkshop"].ToString();
                    onISSUBJECTWORKSHOPCHANGE(ddlissubjectworkshop, ddlswtitle);
                    if (ds.Tables[0].Rows[i]["swid"].ToString() != "")
                    {
                        ddlswtitle.SelectedValue = ds.Tables[0].Rows[i]["swid"].ToString();
                    }
                }
            }
        }

    }
    protected void btnsave_Click(object sender, EventArgs e)
    {
        if (lstcourse.SelectedIndex >= 0)
        {

        }
        else
        {
            Alert("Select Course");
            return;
        }
        if (ddsubject.SelectedIndex >= 0)
        {

        }
        else
        {
            Alert("Select Subject");
            return;
        }
        if (ddlnooflectures.SelectedIndex >= 1)
        {

        }
        else
        {
            Alert("Select No. of Lectures");
            return;
        }
        if (btnsave.Text == "Save Plan")
        {
            if (Check() == false)
            {
                Alert("Please Select All Parameters");
                return;
            }
            clscon.Execqry("insert into tbsessionalplanmaster(nooflectures,swid,createddate,uid) values(" + Convert.ToInt32(ddlnooflectures.SelectedValue) + "," + Convert.ToInt32(ddsubject.SelectedValue) + ",'" + clscon.Indiadt().ToString("yyyy/MM/dd") + "','" + Session["uid"].ToString() + "')");
            int spid = clscon.Return_Int("select max(spid) from tbsessionalplanmaster");
            clscon.Execqry("insert into tbsessionalplancourses(courseid,spid) select courseid,'" + spid + "' from tbcoursemaster where courseid in(" + ReturnCourseids() + ")");

            
            int j = 0;
            for (j = 0; j <= gvlec.Rows.Count - 1; j++)
            {
                
                DropDownList ddlissubjectworkshop = new DropDownList();
                DropDownList ddlswtitle = new DropDownList();
                TextBox txtdes = new TextBox();
                TextBox txthw = new TextBox();
                //FileUpload FileUpload1 = new FileUpload();
                ddlissubjectworkshop = ((DropDownList)(gvlec.Rows[j].FindControl("ddlissubjectworkshop")));
                ddlswtitle = ((DropDownList)(gvlec.Rows[j].FindControl("ddlswtitle")));
                txtdes = ((TextBox)(gvlec.Rows[j].FindControl("txtdes")));
                txthw = ((TextBox)(gvlec.Rows[j].FindControl("txthw")));
                //FileUpload1 =((FileUpload)(gvlec.Rows[j].FindControl("FileUpload1")));
                //string ppt=SaveForm(FileUpload1);
            
                clscon.Execqry("insert into tbsessionalplanchild(spno,spid,issubjectworkshop,swid,description,hw) values('" + (j + 1) + "','" + spid + "'," + Convert.ToInt32(ddlissubjectworkshop.SelectedValue) + "," + Convert.ToInt32(ddlswtitle.SelectedValue) + ",'" + txtdes.Text + "','" + txthw.Text + "')");
            }
            Alert("Saved Successfully");
            Rset();

        }
        ////else
        ////{
        ////    int isworkshop=0;
            
        ////    //clscon.Execqry("delete from tbmodulelecmaster where courseid=" + Convert.ToInt32(lstcourse.SelectedValue) + " and subfareaid=" + Convert.ToInt32(ddsubject.SelectedValue) + " and isworkshop=" + isworkshop + "");
 
        ////    int j = 0;
        ////    for (j = 0; j <= gvlec.Rows.Count - 1; j++)
        ////    {
                
        ////        TextBox txttitle = new TextBox();
        ////        TextBox txtdes = new TextBox();
        ////        TextBox txthw = new TextBox();
        ////        FileUpload FileUpload1 = new FileUpload();
        ////        txttitle = ((TextBox)(gvlec.Rows[j].FindControl("txttitle")));
        ////        txtdes = ((TextBox)(gvlec.Rows[j].FindControl("txtdes")));
        ////        txthw = ((TextBox)(gvlec.Rows[j].FindControl("txthw")));
        ////        FileUpload1 = ((FileUpload)(gvlec.Rows[j].FindControl("FileUpload1")));
        ////        string ppt = SaveForm(FileUpload1);
        ////        int isworkshop1 = 0;
        ////        int maxlecid = Convert.ToInt32(gvlec.DataKeys[j].Value);
        ////        if (maxlecid == 0)
        ////        {
        ////            maxlecid = clscon.autocode("select max(lecid) from tbmodulelecmaster");
        ////            clscon.Execqry("insert into tbmodulelecmaster(lecid,lecname,subfareaid,status,courseid,isworkshop,ltitle,hw,ppt,planfor) values( " + maxlecid + ",'" + txtdes.Text + "'," + Convert.ToInt32(ddsubject.SelectedValue) + ",'Active'," + Convert.ToInt32(lstcourse.SelectedValue) + "," + isworkshop1 + ",'" + txttitle.Text + "','" + txthw.Text + "','" + ppt + "'," +0 + ")");
        ////        }
        ////        else
        ////        {
        ////            if (ppt.Length >= 1)
        ////            {
        ////                clscon.Execqry("update tbmodulelecmaster set lecname='" + txtdes.Text + "',subfareaid=" + Convert.ToInt32(ddsubject.SelectedValue) + ",courseid=" + Convert.ToInt32(lstcourse.SelectedValue) + ",isworkshop=" + isworkshop1 + ",ltitle='" + txttitle.Text + "',hw='" + txthw.Text + "',ppt='" + ppt + "',planfor=" + 0 + " where lecid=" + maxlecid + "");
        ////            }
        ////            else
        ////            {
        ////                clscon.Execqry("update tbmodulelecmaster set lecname='" + txtdes.Text + "',subfareaid=" + Convert.ToInt32(ddsubject.SelectedValue) + ",courseid=" + Convert.ToInt32(lstcourse.SelectedValue) + ",isworkshop=" + isworkshop1 + ",ltitle='" + txttitle.Text + "',hw='" + txthw.Text + "',planfor=" + 0 + " where lecid=" + maxlecid + "");
        ////            }
        ////        }
                
        ////    }
        ////    Alert("Updated Successfully");
        ////    Rset();

        ////}
    }
    private bool Check()
    {
        bool abc = true;
        int j = 0;
        for (j = 0; j <= gvlec.Rows.Count - 1; j++)
        {

            DropDownList ddlissubjectworkshop = new DropDownList();
            DropDownList ddlswtitle = new DropDownList();

            //FileUpload FileUpload1 = new FileUpload();
            ddlissubjectworkshop = ((DropDownList)(gvlec.Rows[j].FindControl("ddlissubjectworkshop")));
            ddlswtitle = ((DropDownList)(gvlec.Rows[j].FindControl("ddlswtitle")));
            if (ddlissubjectworkshop.SelectedIndex >= 1 && ddlswtitle.SelectedIndex >= 1)
            {

            }
            else
            {
                abc = false;
            }
        }
        return abc;
    }
    private string SaveForm(FileUpload FUIMAGE1)
    {
        string s;
        string str = "";
        int j;
        if (FUIMAGE1.PostedFile != null)
        {
            if (FUIMAGE1.PostedFile.ContentLength > 0)
            {
                str = FUIMAGE1.PostedFile.FileName;
                s = str.Substring(str.LastIndexOf(".") + 1);

                if (Convert.ToBoolean(String.Compare(s.ToUpper(), "JPEG") == 0) || Convert.ToBoolean(String.Compare(s.ToUpper(), "GIF") == 0) || Convert.ToBoolean(String.Compare(s.ToUpper(), "JPG") == 0) || Convert.ToBoolean(String.Compare(s.ToUpper(), "BMP") == 0) || Convert.ToBoolean(String.Compare(s.ToUpper(), "PNG") == 0) || Convert.ToBoolean(String.Compare(s.ToUpper(), "TIF") == 0) || Convert.ToBoolean(String.Compare(s.ToUpper(), "PDF") == 0) || Convert.ToBoolean(String.Compare(s.ToUpper(), "DOC") == 0))
                {
                    j = str.LastIndexOf("\\");

                    str = "LEC" + Guid.NewGuid().ToString() + "." + s;
                    FUIMAGE1.PostedFile.SaveAs(Server.MapPath("StudentScopy/" + str));


                    string ppath = str;
                    //clsp.Save();




                }
                else
                {

                    str = "";
                }
            }

        }

        //		Page.RegisterStartupScript("Message",msg1); 				

        return str;
    }
    private void Alert(string str)
    {
        Response.Write("<script>window.alert('"+ str +"')</script>");
    }
    protected void ddlareatype_SelectedIndexChanged(object sender, EventArgs e)
    {
        BindSubjects();
    }
    
    private void BindSubjects()
    {
        //if (ddlareatype.SelectedIndex >= 0)
        //{
        //    if (ddlareatype.SelectedIndex == 0)
        //    {
        //        SetDatainLISTvip(ddsubject, "select * from hrsubfareas where ftype>=1 order by subgroupname", "subgroupname", "subfareaid");
        //    }
        //    else
        //    {
        //        SetDatainLISTvip(ddsubject, "select * from hrsubfareas where ftype=" + Convert.ToInt32(ddlareatype.SelectedValue) + " order by subgroupname", "subgroupname", "subfareaid");
        //    }
        //}
        //else
        //{
        //    ddsubject.Items.Clear();
        //}
    }
    private void BindGrid()
    {
        //string str = "select a.subfareaid,a.subgroupname,a.ftype,b.lecid,b.lecname,b.status from hrsubfareas a,tbmodulelecmaster b where a.subfareaid=b.subfareaid";
        //if (ddlareatype.SelectedIndex >= 1)
        //{
        //    str += " and a.ftype=" + Convert.ToInt32(ddlareatype.SelectedValue) + "";
        //}
        //if (ddsubject.SelectedIndex >= 1)
        //{
        //    str += " and a.subfareaid=" + Convert.ToInt32(ddsubject.SelectedValue) + "";
        //}
        //DataSet ds = new DataSet();
        //clscon.Return_DS(ds, str);
        ////gvlectures.DataSource = ds;
        ////gvlectures.DataBind();

    }
    protected void btnreset_Click(object sender, EventArgs e)
    {
        Rset();
    }
    private void Rset()
    {
        //ddlareatype.SelectedIndex = -1;
        ClearItems(ddsubject);
        ClearItems(lstcourse);
       // rbsession.SelectedIndex = 0;
        ddlnooflectures.SelectedIndex = 0;
        ddlnooflectures.Enabled = true;
        gvlec.DataSource = null;
        gvlec.DataBind();
       // txtlecture.Text = "";
        btnsave.Text = "Save Plan";
     //   BindGrid();
    }
    protected void gvlectures_RowCommand(object sender, GridViewCommandEventArgs e)
    {
        if (e.CommandName == "status")
        {
            int lecid = Convert.ToInt32(e.CommandArgument);
            if (clscon.Return_string("select status from tbmodulelecmaster where lecid=" + lecid + "") == "Active")
            {
                clscon.Execqry("update tbmodulelecmaster set status='Inactive' where lecid=" + lecid + "");
            }
            else
            {
                clscon.Execqry("update tbmodulelecmaster set status='Active' where lecid=" + lecid + "");

            }
            BindGrid();
        }
        else if (e.CommandName == "edit1")
        {
            int lecid = Convert.ToInt32(e.CommandArgument);
            DataSet ds = new DataSet();
            clscon.Return_DS(ds, "select * from tbmodulelecmaster where lecid=" + lecid + "");
            if (ds.Tables[0].Rows.Count >= 1)
            {
               // txtlecture.Text = ds.Tables[0].Rows[0]["lecname"].ToString();
                int subfareaid = Convert.ToInt32(ds.Tables[0].Rows[0]["subfareaid"]);
                int ftype = clscon.Return_Int("select ftype from hrsubfareas where subfareaid=" + subfareaid + "");
               // ddlareatype.SelectedValue = ftype.ToString();
                BindSubjects();
                ddsubject.SelectedValue = ds.Tables[0].Rows[0]["subfareaid"].ToString();
                btnsave.Text = "Update";
                ViewState["lecid"] = lecid;

            }
            
        }
    }
    protected void gvlectures_RowDeleting(object sender, GridViewDeleteEventArgs e)
    {
       // int lecid = Convert.ToInt32(gvlectures.DataKeys[e.RowIndex].Value);
        //PUT CHECK
        //clscon.Execqry("delete from tbmodulelecmaster where lecid=" + lecid + "");
        BindGrid();
    }
    protected void gvlectures_PageIndexChanging(object sender, GridViewPageEventArgs e)
    {
      //  gvlectures.PageIndex = e.NewPageIndex;
        BindGrid();
    }
    protected void ddlareatype_SelectedIndexChanged1(object sender, EventArgs e)
    {
        ClearItems(ddsubject);
        ClearItems(lstcourse);
        btnsave.Text = "Add";
        ddlnooflectures.SelectedIndex = 0;
        ddlnooflectures.Enabled = true;
        gvlec.DataSource = null;
        gvlec.DataBind();
        BindSubjects();
    }
    protected void DropDownList1_SelectedIndexChanged(object sender, EventArgs e)
    {
        if (lstcourse.SelectedIndex >= 0 && ddsubject.SelectedIndex >= 0)
        {
            BindModules();
        }
        else
        {
            Alert("Select Course-Subject");
        }
        
    }
    private void BindModules()
    {
        if (ddlnooflectures.SelectedIndex >= 1)
        {

            ModuleTempBind(ModuleTempCreateColumn());
            gvlec.FooterRow.Visible = false;
        }
        else
        {
            gvlec.DataSource = null;
            gvlec.DataBind();
        }
    }
    protected void gvlec_RowDeleting(object sender, GridViewDeleteEventArgs e)
    {
        int spchildid = Convert.ToInt32(gvlec.DataKeys[e.RowIndex].Value);
        if (spchildid == 0)
        {

        }
        else
        {
            clscon.Execqry("delete from tbsessionalplanchild where spchildid=" + spchildid + "");
            //if (clscon.check("select * from tbsessionalplanchild where spchildid=" + spchildid + "") == true)
            //{
            //    Alert("Unable To Delete Used Lecture in Module Structre");
            //    return;
            //}
            //else
            //{
             

            //}
        }
        MaintainDropDowns();
        DataSet DTLECTURE = new DataSet();
        DTLECTURE = (DataSet)(ViewState["DTLECTURE"]);
       
        DTLECTURE.Tables[0].Rows.RemoveAt(e.RowIndex);

        DTLECTURE.AcceptChanges();

        gvlec.DataSource = DTLECTURE;
        gvlec.DataBind();
        ViewState["DTLECTURE"] = DTLECTURE;
        ReBindDropDowns();
        
        if (gvlec.Rows.Count == 0)
        {
            ddlnooflectures.Enabled = true;
            ddlnooflectures.SelectedIndex = 0;
        }
        try
        {
            ddlnooflectures.SelectedIndex = ddlnooflectures.SelectedIndex - 1;
        }
        catch { }
    }

    public void SetDatainLISTvip(Obout.ListBox.ListBox ctr, string st, string dttext, string dvtext)
    {
        MySqlDataAdapter adp = new MySqlDataAdapter(st, ConfigurationManager.ConnectionStrings["con"].ConnectionString);
        DataSet ds = new DataSet();

        adp.Fill(ds);
        ((Obout.ListBox.ListBox)ctr).Items.Clear();

        ((Obout.ListBox.ListBox)ctr).DataTextField = dttext;
        ((Obout.ListBox.ListBox)ctr).DataValueField = dvtext;
        ((Obout.ListBox.ListBox)ctr).DataSource = ds;
        ((Obout.ListBox.ListBox)ctr).DataBind();


    }
    private void ClearItems(Obout.ListBox.ListBox ctr)
    {
        
        ((Obout.ListBox.ListBox)ctr).Items.Clear();
  
        ((Obout.ListBox.ListBox)ctr).SelectedValue = "";
        ((Obout.ListBox.ListBox)ctr).DataSource = null;
        ((Obout.ListBox.ListBox)ctr).DataBind();
        ((Obout.ListBox.ListBox)ctr).SelectedIndex = -1;
        
      

    }
    private void PPT()
    {
        int i = 0;
        for (i = 0; i <= gvlec.Rows.Count - 1; i++)
        {
          string ppt= ((Label)(gvlec.Rows[i].FindControl("lblhidden"))).Text;
          FileUpload FileUpload1 = new FileUpload();
          HyperLink lnkview = new HyperLink();
          LinkButton lnkchange = new LinkButton();
          LinkButton lnkcancel = new LinkButton();
          FileUpload1 = ((FileUpload)(gvlec.Rows[i].FindControl("FileUpload1")));
          lnkview = ((HyperLink)(gvlec.Rows[i].FindControl("lnkview")));
          lnkchange = ((LinkButton)(gvlec.Rows[i].FindControl("lnkchange")));
          lnkcancel = ((LinkButton)(gvlec.Rows[i].FindControl("lnkcancel")));
          if (ppt.Length >= 1)
          {
              lnkview.NavigateUrl = "~/StudentScopy/" + ppt;
              lnkview.Visible = true;
              lnkchange.Visible = true;
              lnkcancel.Visible = false;
              FileUpload1.Visible = false;
          }
          else
          {
              lnkview.Visible = false;
              lnkchange.Visible = false;
              lnkcancel.Visible = false;
              FileUpload1.Visible = true;
          }
        }
    }
    protected void lstcourse_SelectedIndexChanged(object sender, EventArgs e)
    {
        BindSubjectsNew();
    }
    private string ReturnCourseids()
    {
        string courseids = "0";
        int i = 0;
        int f = 0;
        for (i = 0; i <= lstcourse.Items.Count - 1; i++)
        {
            if (lstcourse.Items[i].Selected == true)
            {
                if (f == 0)
                {
                    courseids = lstcourse.Items[i].Value.ToString();
                    f = 1;
                }
                else
                {
                    courseids += "," + lstcourse.Items[i].Value.ToString();
                }

            }
        }
        //return courseids();
        return courseids;
    }
    private void BindSubjectsNew()
    {
        ClearItems(ddsubject);
        string courseids = "0";
        int i = 0;
        int f = 0;
        for (i = 0; i <= lstcourse.Items.Count - 1; i++)
        {
            if (lstcourse.Items[i].Selected == true)
            {
                if (f == 0)
                {
                    courseids = lstcourse.Items[i].Value.ToString();
                    f = 1;
                }
                else
                {
                    courseids +="," + lstcourse.Items[i].Value.ToString();
                }

            }
        }
        SetDatainLISTvip(ddsubject, "select distinct c.swid,c.swname from tbmoduleswtitle c,tb_subjectworkshopcourses d where c.swid=d.swid and d.courseid in(" + courseids + ") and c.swtype=" + 0 + " and c.swid not in(select distinct a.swid from tbsessionalplanmaster a,tbsessionalplancourses b where a.spid=b.spid and b.courseid in(" + courseids + ") )", "swname", "swid");
    }
    private void BindPreData()
    {
        //if (lstcourse.SelectedIndex >= 0 && ddsubject.SelectedIndex >= 0 && ddlareatype.SelectedIndex >= 0)
        {

            DataSet ds = new DataSet();
           // int isworkshop = ddlareatype.SelectedIndex;

//            clscon.Return_DS(ds, "select * from tbmodulelecmaster where courseid=" + Convert.ToInt32(lstcourse.SelectedValue) + " and subfareaid=" + Convert.ToInt32(ddsubject.SelectedValue) + " and isworkshop=" + isworkshop + "");
            if (ds.Tables[0].Rows.Count >= 1)
            {
             
                ddlnooflectures.SelectedValue = ds.Tables[0].Rows.Count.ToString();
                //rbsession.SelectedIndex = Convert.ToInt32(ds.Tables[0].Rows[0]["planfor"]);
                BindModulesUpdate(ds);
                ddlnooflectures.Enabled = false;
                gvlec.FooterRow.Visible = true;
                btnsave.Text = "Update";
            }
            else
            {

                ddlnooflectures.Enabled = true;
                ddlnooflectures.SelectedIndex = 0;
                gvlec.DataSource = null;
                gvlec.DataBind();
            }
        }
        //else
        //{
            
        //    ddlnooflectures.Enabled = true;
        //    ddlnooflectures.SelectedIndex = 0;
        //    gvlec.DataSource = null;
        //    gvlec.DataBind();
        //}
    }
    private void BindModulesUpdate(DataSet dslec)
    {
        if (ddlnooflectures.SelectedIndex >= 1)
        {

            ModuleTempBindUpdate(ModuleTempCreateColumn(),dslec);
        }
        else
        {
            gvlec.DataSource = null;
            gvlec.DataBind();
        }
    }
    private void ModuleTempBindUpdate(DataTable myTable,DataSet dslec)
    {

        int i;
    
        int p = Convert.ToInt32(ddlnooflectures.SelectedValue);
        for (i = 0; i <= p - 1; i++)
        {
            DataRow row;

            row = myTable.NewRow();
          
                row["lno"] = Convert.ToString(i + 1);

                row["title"] = dslec.Tables[0].Rows[i]["ltitle"].ToString();
                row["description"] = dslec.Tables[0].Rows[i]["lecname"].ToString();
                row["hw"] = dslec.Tables[0].Rows[i]["hw"].ToString();
                row["ppt"] = dslec.Tables[0].Rows[i]["ppt"].ToString();
                row["lecid"] = dslec.Tables[0].Rows[i]["lecid"].ToString();
                myTable.Rows.Add(row);

        }
        DataSet ds = new DataSet();
        ds.Tables.Add(myTable);
        gvlec.DataSource = ds;
        gvlec.DataBind();
        ViewState["DTLECTURE"] = ds;
        PPT();
    }
    protected void lnkaddnew_Click(object sender, EventArgs e)
    {
        MaintainDropDowns();
        DataSet DTLECTURE = new DataSet();
        DTLECTURE = (DataSet)(ViewState["DTLECTURE"]);
        DataRow row;

        row = DTLECTURE.Tables[0].NewRow();

        row["spno"] = Convert.ToString(DTLECTURE.Tables[0].Rows.Count + 1);


        row["description"] = "";
        row["hw"] = "";

        row["spchildid"] = "0";
        row["issubjectworkshop"] = "";
        row["swid"] = "";
        DTLECTURE.Tables[0].Rows.Add(row);
        DTLECTURE.Tables[0].AcceptChanges();
        gvlec.DataSource = DTLECTURE;
        gvlec.DataBind();
        ReBindDropDowns();
        try
        {
            ddlnooflectures.SelectedIndex = ddlnooflectures.SelectedIndex + 1;
        }
        catch { }
    }
    protected void lnkchange_Click(object sender, EventArgs e)
    {
        LinkButton lnkchange = (LinkButton)sender;
        GridViewRow row = (GridViewRow)lnkchange.Parent.Parent;


       
        FileUpload FileUpload1 = new FileUpload();
        HyperLink lnkview = new HyperLink();
        
        LinkButton lnkcancel = new LinkButton();
        FileUpload1 = ((FileUpload)(row.FindControl("FileUpload1")));
        lnkview = ((HyperLink)(row.FindControl("lnkview")));
        
        lnkcancel = ((LinkButton)(row.FindControl("lnkcancel")));
        FileUpload1.Visible = true;
        lnkcancel.Visible = true;
        lnkchange.Visible = false;
        lnkview.Visible = false;
    }
    protected void lnkcancel_Click(object sender, EventArgs e)
    {
        LinkButton lnkcancel = (LinkButton)sender;
        GridViewRow row = (GridViewRow)lnkcancel.Parent.Parent;


      
        FileUpload FileUpload1 = new FileUpload();
        HyperLink lnkview = new HyperLink();
        LinkButton lnkchange = new LinkButton();
     
        FileUpload1 = ((FileUpload)(row.FindControl("FileUpload1")));
        lnkview = ((HyperLink)(row.FindControl("lnkview")));
        lnkchange = ((LinkButton)(row.FindControl("lnkchange")));
     
        FileUpload1.Visible = false;
        lnkcancel.Visible = false;
        lnkchange.Visible = true;
        lnkview.Visible = true;
    }
    protected void ddsubject_SelectedIndexChanged(object sender, EventArgs e)
    {
       
        ClearItems(lstcourse);

        ddlnooflectures.SelectedIndex = 0;
        ddlnooflectures.Enabled = true;
        gvlec.DataSource = null;
        gvlec.DataBind();
        btnsave.Text = "Add";
        if (ddsubject.SelectedIndex >= 0)
        {
           // int isworkshop = ddlareatype.SelectedIndex;

            //SetDatainLISTvip(lstcourse, "select distinct concat(a.coursename,'-',a.courserep) as coursecmb,a.courseid from tbcoursemaster a,tb_subjectworkshopcourses b where a.courseid=b.courseid and b.subfareaid=" + Convert.ToInt32(ddsubject.SelectedValue) + " and b.courseid not in(select distinct b.courseid from tbmodulelecmaster b where b.subfareaid=" + Convert.ToInt32(ddsubject.SelectedValue) + " and b.isworkshop=" + isworkshop + ") order by a.coursename", "coursecmb", "courseid");
          
        }
    }
    protected void ddlissubjectworkshop_SelectedIndexChanged(object sender, EventArgs e)
    {
        DropDownList ddlissubjectworkshop = (DropDownList)sender;
        GridViewRow row = (GridViewRow)ddlissubjectworkshop.Parent.Parent;
        DropDownList ddlswtitle = new DropDownList();
        ddlswtitle = ((DropDownList)(row.FindControl("ddlswtitle")));
        onISSUBJECTWORKSHOPCHANGE(ddlissubjectworkshop,ddlswtitle);
        
    }
    private void onISSUBJECTWORKSHOPCHANGE(DropDownList ddlissubjectworkshop, DropDownList ddlswtitle)
    {
        if (ddlissubjectworkshop.SelectedIndex == 1)
        {
            //SUBJECT
            clscon.SetDatainDDL(ddlswtitle, "select * FROM tbmoduleswtitle where swtype=0 and swid=" + Convert.ToInt32(ddsubject.SelectedValue) + "", "swname", "swid");
            ddlswtitle.SelectedIndex = 1;

        }
        else
        {
            int subjectid = clscon.Return_Int("select subjectid from tbmoduleswtitle where swid=" + Convert.ToInt32(ddsubject.SelectedValue) + "");
            clscon.SetDatainDDL(ddlswtitle, "select * FROM tbmoduleswtitle where swtype=1 and subjectid=" + subjectid + "", "swname", "swid");

        }
    }
}
