<%@ Page Language="C#" Theme="skinFile" MaintainScrollPositionOnPostback="true" MasterPageFile="~/MasterPageEnquiry.master" AutoEventWireup="true" CodeFile="frmSessonalPlanCreate.aspx.cs" Inherits="frmModuleCreateLecturesUnderSubject" Title="Lecture Master" %>

<%@ Register Assembly="obout_ListBox" Namespace="Obout.ListBox" TagPrefix="cc1" %>
<asp:Content runat="server"  ContentPlaceHolderID="head" >
<link href="ControlsAsp/ListBox/resources/custom-styles/grand_gray/style.css" rel="Stylesheet" type="text/css" />		

</asp:Content>
<asp:Content ID="Content1" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
 
    <script src="MULTIFU/jquery.MultiFile.js" type="text/javascript"></script>
	
    <table align="center" width="980">
        <tr>
            <td colspan="6" style="height: 60px; text-align: center">
                <asp:Label ID="Label5" runat="server" CssClass="ptitle" Style="left: 1px" Text="Create Session Plan"></asp:Label></td>
        </tr>
        <tr>
            <td valign="top">
                <asp:Label ID="Label8" runat="server" CssClass="boldtext" Text="Course"></asp:Label></td>
            <td colspan="2" valign="top">
            <span class="grand-gray">
                <cc1:ListBox ID="lstcourse" runat="server"  Width="284px" AutoPostBack="True" OnSelectedIndexChanged="lstcourse_SelectedIndexChanged"></cc1:ListBox></span></td>
            <td colspan="1" valign="top">
                <asp:Label ID="Label1" runat="server" CssClass="boldtext" Text="Choose Subject" Width="110px"></asp:Label></td>
            <td colspan="1" valign="top">
                <cc1:ListBox ID="ddsubject" runat="server" Width="180px" AutoPostBack="True" Height="50px"></cc1:ListBox> 
            </td>
            <td colspan="1">
            </td>
        </tr>
        <tr>
            <td>
                <asp:Label ID="Label7" runat="server" CssClass="boldtext" Text="No. of Lectures" Width="104px"></asp:Label></td>
            <td colspan="2">
                &nbsp;<asp:DropDownList ID="ddlnooflectures" runat="server" Width="70px" AutoPostBack="True" OnSelectedIndexChanged="DropDownList1_SelectedIndexChanged" CssClass="text">
                    <asp:ListItem>--</asp:ListItem>
                    <asp:ListItem>1</asp:ListItem>
                    <asp:ListItem>2</asp:ListItem>
                    <asp:ListItem>3</asp:ListItem>
                    <asp:ListItem>4</asp:ListItem>
                    <asp:ListItem>5</asp:ListItem>
                    <asp:ListItem>6</asp:ListItem>
                    <asp:ListItem>7</asp:ListItem>
                    <asp:ListItem>8</asp:ListItem>
                    <asp:ListItem>7</asp:ListItem>
                    <asp:ListItem>10</asp:ListItem>
                </asp:DropDownList></td>
            <td colspan="1">
            </td>
            <td colspan="1">
            </td>
            <td colspan="1">
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <asp:GridView ID="gvlec" runat="server" AutoGenerateColumns="False" CssClass="text" Width="970px" DataKeyNames="spchildid" OnRowDeleting="gvlec_RowDeleting" ShowFooter="True" SkinID="gvip">
                    <Columns>
                        <asp:BoundField DataField="spno" HeaderText="No." />
                        <asp:TemplateField HeaderText="Type">
                            <ItemTemplate>
                                <table>
                                    <tr>
                                        <td>
                                            <asp:DropDownList ID="ddlissubjectworkshop" runat="server" CssClass="text" Width="100px" OnSelectedIndexChanged="ddlissubjectworkshop_SelectedIndexChanged" AutoPostBack="True">
                                                <asp:ListItem>--Select--</asp:ListItem>
                                                <asp:ListItem Value="0">Subject</asp:ListItem>
                                                <asp:ListItem Value="1">Workshop</asp:ListItem>
                                            </asp:DropDownList></td>
                                    </tr>
                                </table>
                              
                            </ItemTemplate>
                        </asp:TemplateField>
                        <asp:TemplateField HeaderText="S/W Title">
                            <ItemTemplate>
                                <asp:DropDownList ID="ddlswtitle" runat="server" CssClass="text" Width="120px">
                                </asp:DropDownList>
                            </ItemTemplate>
                        </asp:TemplateField>
                        <asp:TemplateField HeaderText="Description">
                            <ItemTemplate>
                                <table>
                                    <tr>
                                        <td>
                                <asp:TextBox ID="txtdes" runat="server" Width="215px" Text='<%# Eval("description") %>' TextMode="MultiLine" Height="100px"></asp:TextBox></td>
                                        <td>
                                            <asp:RequiredFieldValidator ID="tb2" runat="server" ControlToValidate="txtdes" CssClass="text"
                                                ErrorMessage="*" ValidationGroup="a" Display="Dynamic"></asp:RequiredFieldValidator></td>
                                    </tr>
                                </table>
                            </ItemTemplate>
                        </asp:TemplateField>
                        <asp:TemplateField HeaderText="H.W.">
                            <ItemTemplate>
                                <table>
                                    <tr>
                                        <td style="height: 67px">
                                <asp:TextBox ID="txthw" runat="server" TextMode="MultiLine" Width="215px" Text='<%# Eval("hw") %>' Height="100px"></asp:TextBox></td>
                                        <td style="height: 67px">
                                            <asp:RequiredFieldValidator ID="RequiredFieldValidator1" runat="server" ControlToValidate="txthw"
                                                CssClass="text" ErrorMessage="*" ValidationGroup="a" Display="Dynamic"></asp:RequiredFieldValidator></td>
                                    </tr>
                                </table>
                            </ItemTemplate>
                        </asp:TemplateField>
                        <asp:TemplateField HeaderText="Opt">
                            <ItemTemplate>
                                <asp:ImageButton ID="lnkdelete" runat="server" CommandName="delete" ImageUrl="~/Images/deletebyvipan.jpg"
                                    OnClientClick="Javascript:return confirm('Are You Sure To Delete')" ToolTip="Delete Row" />
                            </ItemTemplate>
                            <FooterTemplate>
                                <asp:LinkButton ID="lnkaddnew" runat="server" OnClick="lnkaddnew_Click">Add New</asp:LinkButton>
                            </FooterTemplate>
                        </asp:TemplateField>
                    </Columns>
                </asp:GridView>
            </td>
        </tr>
        <tr>
            <td style="width: 100px">
            </td>
            <td colspan="3" style="text-align: center">
                &nbsp;<asp:Button ID="btnsave" runat="server" CssClass="button" Text="Save Plan" ValidationGroup="a" OnClick="btnsave_Click" />
                <asp:Button ID="btnreset" runat="server" CssClass="button" Text="Reset" OnClick="btnreset_Click" /></td>
            <td colspan="1">
            </td>
            <td colspan="1">
            </td>
        </tr>
        <tr>
            <td colspan="6">
                &nbsp;</td>
        </tr>
    </table>
</asp:Content>

