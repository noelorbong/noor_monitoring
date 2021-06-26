<template>
  <div class="row vld-parent">
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :is-full-page="fullPage"
      :loader="loader"
      :color="loaderColor"
    ></loading>
    <div v-if="pending_hide==false" :class="pending_show==true? 'col-lg-12 col-md-12':'col-lg-4'">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-file"></i>
          Pending Orders ({{ orderPending.length }})
          <span
            style="float:right; color:blue; font-size:11px; cursor:pointer"
            @click="pending_show=false; pending_hide=false; waitlist_hide=false; draft_hide=false; "
            v-if="pending_show==true"
          >Less</span>
          <span
            style="float:right; color:blue; font-size:11px; cursor:pointer"
            @click="pending_show=true; pending_hide=false; waitlist_hide=true; draft_hide=true;"
            v-else
          >More</span>
        </div>
        <div class="card-body" style="margin-bottom:20px; padding-top:5px;">
          <div></div>
          <div v-if="pending_show==false" class="table-responsive-small">
            <SortedTable
              :values="orderPending"
              sort="delivery_date"
              style="width:100%; margin:0px;"
            >
              <thead>
                <th scope="col" style="width:120px; text-align:center">
                  <SortLink name="delivery_date">Delivery Date</SortLink>
                </th>
                <th scope="col" style="text-align:center">
                  <SortLink name="customer.full_name">Name</SortLink>
                </th>
              </thead>
              <tbody slot="body" slot-scope="sort">
                <tr v-for="(order, index) in sort.values" :key="index">
                  <td @click="goToProfile(order)" style="text-align:center; cursor:pointer;">
                    <span
                      v-if="dateOnly(new Date(order.delivery_date)).getTime() > dateOnly(new Date().setDate(new Date().getDate() + 1)).getTime()"
                      class="Advance"
                    >{{ formatDate(order.delivery_date) }}</span>

                    <span
                      v-else
                      :class="formatDate(order.delivery_date) != 'Yesterday' && formatDate(order.delivery_date) != 'Today' &&formatDate(order.delivery_date) != 'Tomorrow' ? 'Delayed':formatDate(order.delivery_date)"
                    >{{ formatDate(order.delivery_date) }}</span>
                  </td>
                  <td
                    @click="goToProfile(order)"
                    class="truncate"
                    style="text-align:center; cursor:pointer; max-width:100px;"
                  >{{ order.customer.full_name }}</td>
                </tr>
              </tbody>
            </SortedTable>
            <p
              style="text-align: center;background: #e4e5e6; font-size: 12px;"
              v-if="orderPending.length == 0"
            >No Pending Order</p>
          </div>
          <div v-else class="table-responsive">
            <SortedTable
              :values="orderPending"
              sort="delivery_date"
              style="width:100%; margin:0px; min-width:640px;"
            >
              <thead>
                <th scope="col" style="width:110px; text-align:center">
                  <SortLink name="order_date">Order Date</SortLink>
                </th>
                <th scope="col" style="width:120px; text-align:center">
                  <SortLink name="delivery_date">Delivery Date</SortLink>
                </th>
                <th scope="col" style="text-align:center">
                  <SortLink name="customer.full_name">Name</SortLink>
                </th>
                <th scope="col" style="width:110px; text-align:center">
                  <SortLink name="customer.municipality">Municipaltiy</SortLink>
                </th>
                <th scope="col" style="text-align:center;">Items</th>
                <th scope="col" style="width:80px">Amount</th>
                <th scope="col" style="width:80px">Ship. Fee</th>
              </thead>
              <tbody slot="body" slot-scope="sort">
                <tr v-for="(order, index) in sort.values" :key="index">
                  <td
                    @click="goToProfile(order)"
                    style="text-align:center; cursor:pointer;"
                  >{{ formatDate(order.order_date) }}</td>
                  <td @click="goToProfile(order)" style="text-align:center; cursor:pointer;">
                    <span
                      v-if="dateOnly(new Date(order.delivery_date)).getTime() > dateOnly(new Date().setDate(new Date().getDate() + 1)).getTime()"
                      class="Advance"
                    >{{ formatDate(order.delivery_date) }}</span>

                    <span
                      v-else
                      :class="formatDate(order.delivery_date) != 'Yesterday' && formatDate(order.delivery_date) != 'Today' &&formatDate(order.delivery_date) != 'Tomorrow' ? 'Delayed':formatDate(order.delivery_date)"
                    >{{ formatDate(order.delivery_date) }}</span>
                  </td>
                  <td
                    class="truncate"
                    @click="goToProfile(order)"
                    style="text-align:center; cursor:pointer;"
                  >{{ order.customer.full_name }}</td>
                  <td
                    @click="goToProfile(order)"
                    style="text-align:center; cursor:pointer;"
                  >{{ order.customer.municipality }}</td>
                  <td style="cursor:pointer;  width: 100px; text-align:center">
                    <div class="custom-tooltip">
                      <span style="color:blue;">{{ countOrders(order.orders) }} items</span>
                      <span class="custom-tooltiptext">
                        <table style="width:100%;">
                          <tr v-for="(product, index) in order.orders" :key="index">
                            <td style="width: 15px;">{{ product.quantity }}</td>
                            <td style="width: 5px;">x</td>
                            <td>
                              {{ product.title }} @₱{{
                              parseFloat(product.price).toFixed(2)
                              }}
                            </td>
                            <td style="width: 70px;">₱ {{ parseFloat(product.total).toFixed(2) }}</td>
                          </tr>
                        </table>
                      </span>
                    </div>
                  </td>
                  <td
                    @click="goToProfile(order)"
                    style="cursor:pointer;"
                  >₱ {{ parseFloat(order.total).toFixed(2) }}</td>
                  <td
                    @click="goToProfile(order)"
                    style="cursor:pointer;"
                  >₱ {{ order.shipping_fee == null ? '0.00': parseFloat(order.shipping_fee).toFixed(2) }}</td>
                </tr>
              </tbody>
            </SortedTable>
            <p
              style="text-align: center;background: #e4e5e6; font-size: 12px;"
              v-if="orderPending.length == 0"
            >No Pending Order</p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-file"></i>
          Allocated Stocks
          <span
            style="float:right; color:blue; font-size:11px; cursor:pointer"
            @click="display.options = true"
            v-if="display.options == false"
          >Show Opt.</span>
          <span
            style="float:right; color:blue; font-size:11px; cursor:pointer"
            @click="display.options = false"
            v-else
          >Hide Opt.</span>
        </div>
        <div class="card-body" style="margin-bottom:20px; padding-top:5px;">
          <p v-if="orderPending.length > 0" style="text-align:center;">
            <span
              v-if="
                orderPending[0].delivery_date != orderPending[orderPending.length-1].delivery_date
              "
            >From</span>
            <span style="font-weight:600">
              {{
              formatDate(orderPending[0].delivery_date)
              }}
            </span>
            <span
              v-if="
                orderPending[0].delivery_date != orderPending[orderPending.length-1].delivery_date
              "
            >
              to
              <span style="font-weight:600">
                {{
                formatDate(orderPending[orderPending.length-1].delivery_date)
                }}
              </span>
            </span>
          </p>
          <div v-if="display.options == true">
            <div style="margin:0px 0px 5px 0px" class="form-group col-12">
              <hr style="margin:0px" />
            </div>
            <div class="row">
              <div class="col-sm-6">
                <h6>
                  Vendor
                  <span
                    v-if="display.vendor == false"
                    @click="display.vendor = true"
                    class="options"
                  >Show</span>
                  <span v-else @click="display.vendor = false" class="options">Hide</span>
                </h6>
                <div
                  :class="
                    display.vendor == true
                      ? 'form-row show_options'
                      : 'form-row hide_options'
                  "
                >
                  <div class="form-group col-12">
                    <div class="row">
                      <div
                        v-for="(vendor, index) in summary.vendors"
                        :key="index"
                        class="col-6"
                        style="display:flex"
                      >
                        <input v-model="vendor.value" type="checkbox" />
                        <label for>{{ vendor.title }}</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <h6>
                  Order Status
                  <span
                    v-if="display.status == false"
                    @click="display.status = true"
                    class="options"
                  >Show</span>
                  <span v-else @click="display.status = false" class="options">Hide</span>
                </h6>
                <div
                  :class="
                    display.status == true
                      ? 'form-row show_options'
                      : 'form-row hide_options'
                  "
                >
                  <div class="form-group col-12">
                    <div class="row">
                      <div class="col-6" style="display:flex">
                        <input v-model="summary.order_status.fulfilled" type="checkbox" />
                        <label for>Fulfilled</label>
                      </div>
                      <div class="col-6" style="display:flex">
                        <input v-model="summary.order_status.unfulfilled" type="checkbox" />
                        <label for>Unfulfilled</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <h6>
                  Tags
                  <span
                    v-if="display.tags == false"
                    @click="display.tags = true"
                    class="options"
                  >Show</span>
                  <span v-else @click="display.tags = false" class="options">Hide</span>
                </h6>
                <div
                  :class="
                    display.tags == true
                      ? 'form-row show_options'
                      : 'form-row hide_options'
                  "
                >
                  <div class="form-group col-12">
                    <div class="row">
                      <div
                        v-for="(tag, index) in summary.tags"
                        :key="index"
                        class="col-6"
                        style="display:flex"
                      >
                        <input v-model="tag.value" type="checkbox" />
                        <label for>{{ tag.title }}</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="table-responsive2">
            <SortedTable
              :values="summaryReport"
              sort="title"
              class="table table-striped"
              style="padding:0px;"
            >
              <thead>
                <tr>
                  <th scope="col" style="text-align:center">
                    <SortLink name="title">Product ({{ summaryReport.length }})</SortLink>
                  </th>
                  <th v-if="pending_show == true" scope="col" style="text-align:center">
                    <SortLink name="vendor">Vendor</SortLink>
                  </th>
                  <th scope="col" style="text-align:center">
                    <SortLink name="quantity">Quant.</SortLink>
                  </th>
                </tr>
              </thead>
              <tbody slot="body" slot-scope="sort">
                <tr v-for="(order, index) in sort.values" :key="index">
                  <td
                    :title="order.title"
                    class="truncate"
                    style="text-align:center; max-width:100px;"
                  >{{ order.title }}</td>
                  <td v-if="pending_show == true" style="text-align:center;">{{ order.vendor }}</td>
                  <td style="width:75px">{{ order.quantity }}</td>
                </tr>
              </tbody>
            </SortedTable>
            <p
              style="text-align: center;background: #e4e5e6; font-size: 15px;"
              v-if="summaryReport.length == 0"
            >No products found.</p>
          </div>
        </div>
      </div>
    </div>
    <div v-if="waitlist_hide==false" :class="waitlist_show==true? 'col-lg-12 col-md-12':'col-lg-4'">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-file"></i>
          Waitlist Orders ({{ orderWaiting.length }})
          <span
            style="float:right; color:blue; font-size:11px; cursor:pointer"
            @click="waitlist_show=false; pending_hide=false; waitlist_hide=false; draft_hide=false;"
            v-if="waitlist_show==true"
          >Less</span>
          <span
            style="float:right; color:blue; font-size:11px; cursor:pointer"
            @click="waitlist_show=true; pending_hide=true; waitlist_hide=false; draft_hide=true;"
            v-else
          >More</span>
        </div>
        <div class="card-body" style="margin-bottom:20px; padding-top:5px;">
          <div v-if="waitlist_show==false" class="table-responsive-small">
            <SortedTable
              :values="orderWaiting"
              sort="delivery_date"
              style="width:100%; margin:0px;"
            >
              <thead>
                <th scope="col" style="width:120px; text-align:center">
                  <SortLink name="delivery_date">Delivery Date</SortLink>
                </th>
                <th scope="col" style="text-align:center">
                  <SortLink name="customer.full_name">Name</SortLink>
                </th>
              </thead>
              <tbody slot="body" slot-scope="sort">
                <tr v-for="(order, index) in sort.values" :key="index">
                  <td @click="goToProfile(order)" style="text-align:center; cursor:pointer;">
                    <span
                      v-if="dateOnly(new Date(order.delivery_date)).getTime() > dateOnly(new Date().setDate(new Date().getDate() + 1)).getTime()"
                      class="Advance"
                    >{{ formatDate(order.delivery_date) }}</span>

                    <span
                      v-else
                      :class="formatDate(order.delivery_date) != 'Yesterday' && formatDate(order.delivery_date) != 'Today' &&formatDate(order.delivery_date) != 'Tomorrow' ? 'Delayed':formatDate(order.delivery_date)"
                    >{{ formatDate(order.delivery_date) }}</span>
                  </td>
                  <td
                    @click="goToProfile(order)"
                    class="truncate"
                    style="text-align:center; cursor:pointer; max-width:100px;"
                  >{{ order.customer.full_name }}</td>
                </tr>
              </tbody>
            </SortedTable>
            <p
              style="text-align: center;background: #e4e5e6; font-size: 12px;"
              v-if="orderWaiting.length == 0"
            >No Waitlist Order</p>
          </div>
          <div v-else class="table-responsive">
            <SortedTable
              :values="orderWaiting"
              sort="delivery_date"
              style="width:100%; margin:0px; min-width:640px;"
            >
              <thead>
                <th scope="col" style="width:110px; text-align:center">
                  <SortLink name="order_date">Order Date</SortLink>
                </th>
                <th scope="col" style="width:120px; text-align:center">
                  <SortLink name="delivery_date">Delivery Date</SortLink>
                </th>
                <th scope="col" style="text-align:center">
                  <SortLink name="customer.full_name">Name</SortLink>
                </th>
                <th scope="col" style="width:110px; text-align:center">
                  <SortLink name="customer.municipality">Municipaltiy</SortLink>
                </th>
                <th scope="col" style="text-align:center;">Items</th>
                <th scope="col" style="width:80px">Amount</th>
                <th scope="col" style="width:80px">Ship. Fee</th>
              </thead>
              <tbody slot="body" slot-scope="sort">
                <tr v-for="(order, index) in sort.values" :key="index">
                  <td
                    @click="goToProfile(order)"
                    style="text-align:center; cursor:pointer;"
                  >{{ formatDate(order.order_date) }}</td>
                  <td @click="goToProfile(order)" style="text-align:center; cursor:pointer;">
                    <span
                      v-if="dateOnly(new Date(order.delivery_date)).getTime() > dateOnly(new Date().setDate(new Date().getDate() + 1)).getTime()"
                      class="Advance"
                    >{{ formatDate(order.delivery_date) }}</span>

                    <span
                      v-else
                      :class="formatDate(order.delivery_date) != 'Yesterday' && formatDate(order.delivery_date) != 'Today' &&formatDate(order.delivery_date) != 'Tomorrow' ? 'Delayed':formatDate(order.delivery_date)"
                    >{{ formatDate(order.delivery_date) }}</span>
                  </td>
                  <td
                    class="truncate"
                    @click="goToProfile(order)"
                    style="text-align:center; cursor:pointer;"
                  >{{ order.customer.full_name }}</td>
                  <td
                    @click="goToProfile(order)"
                    style="text-align:center; cursor:pointer;"
                  >{{ order.customer.municipality }}</td>
                  <td style="cursor:pointer;  width: 100px; text-align:center">
                    <div class="custom-tooltip">
                      <span style="color:blue;">{{ countOrders(order.orders) }} items</span>
                      <span class="custom-tooltiptext">
                        <table style="width:100%;">
                          <tr v-for="(product, index) in order.orders" :key="index">
                            <td style="width: 15px;">{{ product.quantity }}</td>
                            <td style="width: 5px;">x</td>
                            <td>
                              {{ product.title }} @₱{{
                              parseFloat(product.price).toFixed(2)
                              }}
                            </td>
                            <td style="width: 70px;">₱ {{ parseFloat(product.total).toFixed(2) }}</td>
                          </tr>
                        </table>
                      </span>
                    </div>
                  </td>
                  <td
                    @click="goToProfile(order)"
                    style="cursor:pointer;"
                  >₱ {{ parseFloat(order.total).toFixed(2) }}</td>
                  <td
                    @click="goToProfile(order)"
                    style="cursor:pointer;"
                  >₱ {{ order.shipping_fee == null ? '0.00': parseFloat(order.shipping_fee).toFixed(2) }}</td>
                </tr>
              </tbody>
            </SortedTable>
            <p
              style="text-align: center;background: #e4e5e6; font-size: 12px;"
              v-if="orderWaiting.length == 0"
            >No Waitlist Order</p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-file"></i>
          Wait List Items
          <span
            style="float:right; color:blue; font-size:11px; cursor:pointer"
            @click="stocks.options = true"
            v-if="stocks.options == false"
          >Show Opt.</span>
          <span
            style="float:right; color:blue; font-size:11px; cursor:pointer"
            @click="stocks.options = false"
            v-else
          >Hide Opt.</span>
        </div>
        <div class="card-body" style="margin-bottom:20px; padding-top:5px;">
          <div class="col-sm-12" v-if="stocks.options == true">
            <h6>Vendor</h6>
            <div class="form-row">
              <div class="form-group col-12">
                <div class="row">
                  <div
                    v-for="(vendor, index) in stocks.vendors"
                    :key="index"
                    class="col-4"
                    style="display:flex"
                  >
                    <input v-model="vendor.value" type="checkbox" />
                    <label for>{{ vendor.title }}</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="table-responsive2">
            <SortedTable
              :values="waitListItem"
              sort="title"
              class="table table-striped"
              style="padding:0px;"
            >
              <thead>
                <tr>
                  <th scope="col">
                    <SortLink name="title">Product ({{ waitListItem.length }})</SortLink>
                  </th>
                  <th v-if="waitlist_show==true" scope="col" style="text-align:center">
                    <SortLink name="vendor">Vendor</SortLink>
                  </th>
                  <th scope="col">
                    <SortLink name="quantity">Quant.</SortLink>
                  </th>
                </tr>
              </thead>
              <tbody slot="body" slot-scope="sort">
                <tr v-for="(order, index) in sort.values" :key="index">
                  <td
                    :title="order.title"
                    class="truncate"
                    style="text-align:center; max-width:100px;"
                  >{{ order.title }}</td>
                  <td v-if="waitlist_show==true" style="text-align:center;">{{ order.vendor }}</td>
                  <td style="width:75px">{{ order.quantity }}</td>
                </tr>
              </tbody>
            </SortedTable>

            <p
              style="text-align: center;background: #e4e5e6; font-size: 15px;"
              v-if="waitListItem.length == 0"
            >No products found.</p>
          </div>
        </div>
      </div>
    </div>
    <div v-if="draft_hide==false" :class="draft_show==true? 'col-lg-12 col-md-12':'col-lg-4'">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-file"></i>
          Draft Orders ({{ orderDraft.length }})
          <span
            style="float:right; color:blue; font-size:11px; cursor:pointer"
            @click="draft_show=false; pending_hide=false; waitlist_hide=false; draft_hide=false;"
            v-if="draft_show==true"
          >Less</span>
          <span
            style="float:right; color:blue; font-size:11px; cursor:pointer"
            @click="draft_show=true; pending_hide=true; waitlist_hide=true; draft_hide=false;"
            v-else
          >More</span>
        </div>
        <div class="card-body" style="margin-bottom:20px; padding-top:5px;">
          <div v-if="draft_show==false" class="table-responsive-small">
            <SortedTable :values="orderDraft" sort="delivery_date" style="width:100%; margin:0px;">
              <thead>
                <th scope="col" style="width:120px; text-align:center">
                  <SortLink name="delivery_date">Delivery Date</SortLink>
                </th>
                <th scope="col" style="text-align:center">
                  <SortLink name="customer.full_name">Name</SortLink>
                </th>
              </thead>
              <tbody slot="body" slot-scope="sort">
                <tr v-for="(order, index) in sort.values" :key="index">
                  <td @click="goToProfile(order)" style="text-align:center; cursor:pointer;">
                    <span
                      v-if="dateOnly(new Date(order.delivery_date)).getTime() > dateOnly(new Date().setDate(new Date().getDate() + 1)).getTime()"
                      class="Advance"
                    >{{ formatDate(order.delivery_date) }}</span>

                    <span
                      v-else
                      :class="formatDate(order.delivery_date) != 'Yesterday' && formatDate(order.delivery_date) != 'Today' &&formatDate(order.delivery_date) != 'Tomorrow' ? 'Delayed':formatDate(order.delivery_date)"
                    >{{ formatDate(order.delivery_date) }}</span>
                  </td>
                  <td
                    @click="goToProfile(order)"
                    class="truncate"
                    style="text-align:center; cursor:pointer; max-width:100px;"
                  >{{ order.customer.full_name }}</td>
                </tr>
              </tbody>
            </SortedTable>
            <p
              style="text-align: center;background: #e4e5e6; font-size: 12px;"
              v-if="orderDraft.length == 0"
            >No Draft Order</p>
          </div>
          <div v-else class="table-responsive">
            <SortedTable
              :values="orderDraft"
              sort="delivery_date"
              style="width:100%; margin:0px; min-width:640px;"
            >
              <thead>
                <th scope="col" style="width:110px; text-align:center">
                  <SortLink name="order_date">Order Date</SortLink>
                </th>
                <th scope="col" style="width:120px; text-align:center">
                  <SortLink name="delivery_date">Delivery Date</SortLink>
                </th>
                <th scope="col" style="text-align:center">
                  <SortLink name="customer.full_name">Name</SortLink>
                </th>
                <th scope="col" style="width:110px; text-align:center">
                  <SortLink name="customer.municipality">Municipaltiy</SortLink>
                </th>
                <th scope="col" style="text-align:center;">Items</th>
                <th scope="col" style="width:80px">Amount</th>
                <th scope="col" style="width:80px">Ship. Fee</th>
              </thead>
              <tbody slot="body" slot-scope="sort">
                <tr v-for="(order, index) in sort.values" :key="index">
                  <td
                    @click="goToProfile(order)"
                    style="text-align:center; cursor:pointer;"
                  >{{ formatDate(order.order_date) }}</td>
                  <td @click="goToProfile(order)" style="text-align:center; cursor:pointer;">
                    <span
                      v-if="dateOnly(new Date(order.delivery_date)).getTime() > dateOnly(new Date().setDate(new Date().getDate() + 1)).getTime()"
                      class="Advance"
                    >{{ formatDate(order.delivery_date) }}</span>

                    <span
                      v-else
                      :class="formatDate(order.delivery_date) != 'Yesterday' && formatDate(order.delivery_date) != 'Today' &&formatDate(order.delivery_date) != 'Tomorrow' ? 'Delayed':formatDate(order.delivery_date)"
                    >{{ formatDate(order.delivery_date) }}</span>
                  </td>
                  <td
                    class="truncate"
                    @click="goToProfile(order)"
                    style="text-align:center; cursor:pointer;"
                  >{{ order.customer.full_name }}</td>
                  <td
                    @click="goToProfile(order)"
                    style="text-align:center; cursor:pointer;"
                  >{{ order.customer.municipality }}</td>
                  <td style="cursor:pointer;  width: 100px; text-align:center">
                    <div class="custom-tooltip">
                      <span style="color:blue;">{{ countOrders(order.orders) }} items</span>
                      <span class="custom-tooltiptext">
                        <table style="width:100%;">
                          <tr v-for="(product, index) in order.orders" :key="index">
                            <td style="width: 15px;">{{ product.quantity }}</td>
                            <td style="width: 5px;">x</td>
                            <td>
                              {{ product.title }} @₱{{
                              parseFloat(product.price).toFixed(2)
                              }}
                            </td>
                            <td style="width: 70px;">₱ {{ parseFloat(product.total).toFixed(2) }}</td>
                          </tr>
                        </table>
                      </span>
                    </div>
                  </td>
                  <td
                    @click="goToProfile(order)"
                    style="cursor:pointer;"
                  >₱ {{ parseFloat(order.total).toFixed(2) }}</td>
                  <td
                    @click="goToProfile(order)"
                    style="cursor:pointer;"
                  >₱ {{ order.shipping_fee == null ? '0.00': parseFloat(order.shipping_fee).toFixed(2) }}</td>
                </tr>
              </tbody>
            </SortedTable>
            <p
              style="text-align: center;background: #e4e5e6; font-size: 12px;"
              v-if="orderWaiting.length == 0"
            >No Draft Order</p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-file"></i>
          Draft Items
          <span
            style="float:right; color:blue; font-size:11px; cursor:pointer"
            @click="stocks.draft.options = true"
            v-if="stocks.draft.options == false"
          >Show Opt.</span>
          <span
            style="float:right; color:blue; font-size:11px; cursor:pointer"
            @click="stocks.draft.options = false"
            v-else
          >Hide Opt.</span>
        </div>
        <div class="card-body" style="margin-bottom:20px; padding-top:5px;">
          <div class="col-sm-12" v-if="stocks.draft.options == true">
            <h6>Vendor</h6>
            <div class="form-row">
              <div class="form-group col-12">
                <div class="row">
                  <div
                    v-for="(vendor, index) in stocks.draftvendors"
                    :key="index"
                    class="col-4"
                    style="display:flex"
                  >
                    <input v-model="vendor.value" type="checkbox" />
                    <label for>{{ vendor.title }}</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="table-responsive2">
            <SortedTable
              :values="draftListItem"
              sort="title"
              class="table table-striped"
              style="padding:0px;"
            >
              <thead>
                <tr>
                  <th scope="col">
                    <SortLink name="title">Product ({{ draftListItem.length }})</SortLink>
                  </th>
                  <th v-if="draft_show==true" scope="col" style="text-align:center">
                    <SortLink name="vendor">Vendor</SortLink>
                  </th>
                  <th scope="col">
                    <SortLink name="quantity">Quant.</SortLink>
                  </th>
                </tr>
              </thead>
              <tbody slot="body" slot-scope="sort">
                <tr v-for="(order, index) in sort.values" :key="index">
                  <td
                    :title="order.title"
                    class="truncate"
                    style="text-align:center; max-width:100px;"
                  >{{ order.title }}</td>
                  <td v-if="draft_show==true" style="text-align:center;">{{ order.vendor }}</td>
                  <td style="width:75px">{{ order.quantity }}</td>
                </tr>
              </tbody>
            </SortedTable>

            <p
              style="text-align: center;background: #e4e5e6; font-size: 15px;"
              v-if="draftListItem.length == 0"
            >No products found.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      pending_show: false,
      waitlist_show: false,
      draft_show: false,
      pending_hide: false,
      waitlist_hide: false,
      draft_hide: false,
      orders: [],
      products: [],
      stocks: {
        draft: { options: false },
        options: false,
        vendors: [{ title: "No Vendor", value: true }],
        draftvendors: [{ title: "No Vendor", value: true }]
      },
      display: {
        options: false,
        vendor: false,
        order_type: false,
        status: false,
        tags: false
      },
      summary: {
        order_date: {
          start_date: this.getMonday(this.getDate(), 0),
          end_date: this.getSunday(this.getDate(), 0)
        },
        order_type: {
          pending: true,
          paid: true,
          draft: false,
          waitlist: false,
          canceled: false
        },
        order_status: {
          fulfilled: false,
          unfulfilled: true
        },
        sorting: {
          type: "Product",
          order: "Ascending"
        },
        tags: [{ title: "No Tag", value: true }],
        vendors: [{ title: "No Vendor", value: true }]
      },
      report: {
        start_date: this.getMonday(this.getDate(), 0),
        end_date: this.getSunday(this.getDate(), 0),
        rider_fee: true,
        delivered: {
          quantity: 0,
          amount: 0
        }
      },
      fullPage: false,
      isLoading: false,
      loader: "dots",
      loaderColor: "rgb(77, 189, 116)"
    };
  },
  created: function() {
    let app = this;
    // var g1 = new Date();
    // var g2 = new Date("2020-07-27");
    // console.log("g1: " + app.dateOnly(g1).getTime());
    // console.log("g2: " + app.dateOnly(g2).getTime());

    if (app.$store.state.orders) {
      if (app.$store.state.orders.length > 0) {
        app.orders = JSON.parse(JSON.stringify(this.$store.state.orders));
        app.getAllTags();
      } else {
        app.loadOrders();
      }
    } else {
      app.loadOrders();
    }

    if (app.$store.state.products) {
      if (app.$store.state.products.length > 0) {
        app.products = JSON.parse(JSON.stringify(this.$store.state.products));
        var local_tags = app.products
          .map(item => item.vendor)
          .filter((value, index, self) => self.indexOf(value) === index);

        $.each(local_tags, function(key1, value1) {
          if (value1) {
            app.summary.vendors.push({
              title: value1,
              value: true
            });
            app.stocks.vendors.push({
              title: value1,
              value: true
            });

            app.stocks.draftvendors.push({
              title: value1,
              value: true
            });
          }
        });
        // console.log(local_tags);
      } else {
        app.loadproducts();
      }
    } else {
      app.loadproducts();
    }
  },
  computed: {
    statusDatefilteredOrders: function() {
      let app = this;
      return app.orders.filter(
        i =>
          i.order_date >= app.report.start_date &&
          i.order_date <= app.report.end_date
      );

      return [];
    },
    deliveredOrders: function() {
      let app = this;

      var delivered_orders = app.statusDatefilteredOrders.filter(
        i => i.fulfilled == 1 && i.status == 2
      );
      var amount = 0;
      var quantity = 0;
      $.each(delivered_orders, function(key, value) {
        quantity += 1;
        if (app.report.rider_fee == true) {
          amount += value.total;
        } else {
          amount += value.total - value.shipping_fee;
        }
      });

      return { quantity: quantity, amount: amount };
    },
    pendingOrders: function() {
      let app = this;

      var delivered_orders = app.statusDatefilteredOrders.filter(
        i => i.status == 1
      );
      var amount = 0;
      var quantity = 0;
      $.each(delivered_orders, function(key, value) {
        quantity += 1;
        if (app.report.rider_fee == true) {
          amount += value.total;
        } else {
          amount += value.total - value.shipping_fee;
        }
      });

      return { quantity: quantity, amount: amount };
    },
    waitlistOrders: function() {
      let app = this;

      var delivered_orders = app.statusDatefilteredOrders.filter(
        i => i.status == 3
      );
      var amount = 0;
      var quantity = 0;
      $.each(delivered_orders, function(key, value) {
        quantity += 1;
        if (app.report.rider_fee == true) {
          amount += value.total;
        } else {
          amount += value.total - value.shipping_fee;
        }
      });

      return { quantity: quantity, amount: amount };
    },
    canceledOrders: function() {
      let app = this;

      var delivered_orders = app.statusDatefilteredOrders.filter(
        i => i.status == 4
      );
      var amount = 0;
      var quantity = 0;
      $.each(delivered_orders, function(key, value) {
        quantity += 1;
        if (app.report.rider_fee == true) {
          amount += value.total;
        } else {
          amount += value.total - value.shipping_fee;
        }
      });

      return { quantity: quantity, amount: amount };
    },
    orderPending: function() {
      function compare(a, b) {
        if (a.delivery_date < b.delivery_date) return -1;
        if (a.delivery_date > b.delivery_date) return 1;
        return 0;
      }
      return this.orders.filter(i => i.status == 1).sort(compare);
    },
    orderWaiting: function() {
      function compare(a, b) {
        if (a.delivery_date < b.delivery_date) return -1;
        if (a.delivery_date > b.delivery_date) return 1;
        return 0;
      }

      return this.orders.filter(i => i.status == 3).sort(compare);
    },
    orderDraft: function() {
      function compare(a, b) {
        if (a.delivery_date < b.delivery_date) return -1;
        if (a.delivery_date > b.delivery_date) return 1;
        return 0;
      }

      return this.orders.filter(i => i.status == 0).sort(compare);
    },

    statusDatefilteredOrders: function() {
      let app = this;
      if (
        app.summary.order_status.fulfilled == true &&
        app.summary.order_status.unfulfilled == true
      ) {
        return app.orders.filter(
          i =>
            i.delivery_date >= app.summary.order_date.start_date &&
            i.delivery_date <= app.summary.order_date.end_date
        );
      }

      if (
        app.summary.order_status.fulfilled == true &&
        app.summary.order_status.unfulfilled == false
      ) {
        return app.orders.filter(
          i =>
            i.fulfilled == 1 &&
            i.delivery_date >= app.summary.order_date.start_date &&
            i.delivery_date <= app.summary.order_date.end_date
        );
      }

      if (
        app.summary.order_status.fulfilled == false &&
        app.summary.order_status.unfulfilled == true
      ) {
        return app.orders.filter(
          i =>
            i.fulfilled == 0 &&
            i.delivery_date >= app.summary.order_date.start_date &&
            i.delivery_date <= app.summary.order_date.end_date
        );
      }

      return [];
    },
    orderTypeFilter: function() {
      let app = this;
      var local_order_type = [];
      return app.orders.filter(i => i.status == 1);
    },
    orderTagFilter: function() {
      let app = this;
      var local_tags = [];
      var local_orders = [];
      $.each(app.summary.tags, function(key, value1) {
        if (value1.value) {
          local_tags.push(value1.title);
        }
      });

      $.each(app.orderTypeFilter, function(key1, value1) {
        if (value1.tags) {
          $.each(value1.tags.split(","), function(key2, value2) {
            if (local_tags.includes(value2)) {
              local_orders.push(value1);
              return false;
            }
          });
        } else {
          if (app.summary.tags[0].value) {
            local_orders.push(value1);
          }
        }
      });

      return local_orders;
    },
    vendorFilter: function() {
      let app = this;
      var local_vendors = [];

      $.each(app.summary.vendors, function(key, value1) {
        if (value1.title) {
          if (value1.value) {
            local_vendors.push(value1.title);
          }
        }
      });

      return local_vendors;
    },
    stocksVendorFilter: function() {
      let app = this;
      var local_vendors = [];

      $.each(app.stocks.vendors, function(key, value1) {
        if (value1.title) {
          if (value1.value) {
            local_vendors.push(value1.title);
          }
        }
      });

      return local_vendors;
    },
    stocksDraftVendorFilter: function() {
      let app = this;
      var local_vendors = [];

      $.each(app.stocks.draftvendors, function(key, value1) {
        if (value1.title) {
          if (value1.value) {
            local_vendors.push(value1.title);
          }
        }
      });

      return local_vendors;
    },
    summaryReport: function() {
      var product_list_id = [];
      var prodcut_list_f = [];
      let app = this;
      app.quantity_total = 0;
      $.each(app.orderTagFilter, function(key1, value1) {
        $.each(value1.orders, function(key2, value2) {
          if (
            !product_list_id.includes(value2.product_id) &&
            (app.vendorFilter.includes(value2.vendor) ||
              (!value2.vendor && app.summary.vendors[0].value))
          ) {
            product_list_id.push(value2.product_id);

            var quantity = 0;
            var price = 0;
            $.each(app.orderTagFilter, function(key3, value3) {
              $.each(value3.orders, function(key4, value4) {
                if (value4.product_id == value2.product_id) {
                  quantity += value4.quantity;
                  price += value4.quantity * value4.price;
                }
              });
            });

            app.quantity_total += quantity;
            prodcut_list_f.push({
              title: value2.title,
              vendor: value2.vendor,
              quantity: quantity,
              price: price
            });
          }
        });
      });
      return prodcut_list_f;
    },
    waitListItem: function() {
      var product_list_id = [];
      var prodcut_list_f = [];
      let app = this;
      var orderWaitListFiter = JSON.parse(
        JSON.stringify(app.orders.filter(i => i.status == 3))
      );

      $.each(orderWaitListFiter, function(key1, value1) {
        $.each(value1.orders, function(key2, value2) {
          if (
            !product_list_id.includes(value2.product_id) &&
            (app.stocksVendorFilter.includes(value2.vendor) ||
              (!value2.vendor && app.stocks.vendors[0].value))
          ) {
            product_list_id.push(value2.product_id);

            var quantity = 0;
            var price = 0;
            $.each(orderWaitListFiter, function(key3, value3) {
              $.each(value3.orders, function(key4, value4) {
                if (value4.product_id == value2.product_id) {
                  quantity += value4.quantity;
                  price += value4.quantity * value4.price;
                }
              });
            });

            prodcut_list_f.push({
              title: value2.title,
              vendor: value2.vendor,
              quantity: quantity,
              price: price
            });
          }
        });
      });

      return prodcut_list_f;
    },
    draftListItem: function() {
      var product_list_id = [];
      var prodcut_list_f = [];
      let app = this;
      var orderDraftListFiter = JSON.parse(
        JSON.stringify(app.orders.filter(i => i.status == 0))
      );

      $.each(orderDraftListFiter, function(key1, value1) {
        $.each(value1.orders, function(key2, value2) {
          if (
            !product_list_id.includes(value2.product_id) &&
            (app.stocksDraftVendorFilter.includes(value2.vendor) ||
              (!value2.vendor && app.stocks.draftvendors[0].value))
          ) {
            product_list_id.push(value2.product_id);

            var quantity = 0;
            var price = 0;
            $.each(orderDraftListFiter, function(key3, value3) {
              $.each(value3.orders, function(key4, value4) {
                if (value4.product_id == value2.product_id) {
                  quantity += value4.quantity;
                  price += value4.quantity * value4.price;
                }
              });
            });

            prodcut_list_f.push({
              title: value2.title,
              vendor: value2.vendor,
              quantity: quantity,
              price: price
            });
          }
        });
      });

      return prodcut_list_f;
    }
  },
  methods: {
    currentDate() {
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1;
      var yyyy = today.getFullYear();

      if (dd < 10) {
        dd = "0" + dd;
      }

      if (mm < 10) {
        mm = "0" + mm;
      }
      today = yyyy + "-" + mm + "-" + dd;
      return today;
    },
    dateOnly(date) {
      var today = new Date(date);
      var dd = today.getDate();
      var mm = today.getMonth() + 1;
      var yyyy = today.getFullYear();

      if (dd < 10) {
        dd = "0" + dd;
      }

      if (mm < 10) {
        mm = "0" + mm;
      }
      today = yyyy + "-" + mm + "-" + dd;
      return new Date(today);
    },
    formatDate(date) {
      let app = this;
      var today = new Date(date);
      var currentDate = this.currentDate();
      // console.log("today: " + app.DateOnly(today));
      // console.log("Date: " + app.DateOnly(new Date()));
      if (
        app.dateOnly(today).toString() == app.dateOnly(new Date()).toString()
      ) {
        today = "Today";
      } else if (
        app.dateOnly(today).toString() ==
        app.dateOnly(new Date().setDate(new Date().getDate() + 1)).toString()
      ) {
        today = "Tomorrow";
      } else if (
        app.dateOnly(today).toString() ==
        app.dateOnly(new Date().setDate(new Date().getDate() - 1)).toString()
      ) {
        today = "Yesterday";
      } else if (
        today >= new Date(this.getMonday(currentDate, 0)) &&
        today <= new Date(this.getSunday(currentDate, 0))
      ) {
        // console.log(this.getMonday(currentDate, 0));
        // console.log(this.getSunday(currentDate, 0));
        var a = new Date(date);
        var weekdays = new Array(7);
        weekdays[0] = "Sunday";
        weekdays[1] = "Monday";
        weekdays[2] = "Tuesday";
        weekdays[3] = "Wednesday";
        weekdays[4] = "Thursday";
        weekdays[5] = "Friday";
        weekdays[6] = "Saturday";
        today = weekdays[a.getDay()];
      } else {
        var dd = today.getDate();
        var mm = today.getMonth();
        var yyyy = today.getFullYear();

        if (dd < 10) {
          dd = "0" + dd;
        }
        const monthNames = [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
          "August",
          "September",
          "October",
          "November",
          "December"
        ];
        today = monthNames[mm] + " " + dd + ", " + yyyy;
      }

      // document.getElementById("myId").innerHTML = r;

      return today;
    },
    goToProfile(new_order_view) {
      this.$store.commit("changeOrder", new_order_view);
      if (new_order_view.status == 0) {
        this.$router.push({
          name: "OrderDraft"
        });
      } else {
        this.$router.push({
          name: "OrderStatus"
        });
      }
    },
    getDate() {
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1;
      var yyyy = today.getFullYear();

      if (dd < 10) {
        dd = "0" + dd;
      }

      if (mm < 10) {
        mm = "0" + mm;
      }
      today = yyyy + "-" + mm + "-" + dd;
      return today;
    },
    getMonday(d, addMinus) {
      d = new Date(d);
      var day = d.getDay(),
        diff = d.getDate() - day + (day == 0 ? -6 : 1) + addMinus; // adjust when day is sunday

      var newDate = new Date(d.setDate(diff));

      var today = new Date(newDate);
      var dd = today.getDate();
      var mm = today.getMonth() + 1;
      var yyyy = today.getFullYear();

      if (dd < 10) {
        dd = "0" + dd;
      }

      if (mm < 10) {
        mm = "0" + mm;
      }
      today = yyyy + "-" + mm + "-" + dd;
      return today;
    },
    getSunday(d, addMinus) {
      d = new Date(d);
      var day = 0;
      var diff = 0;
      if (d.getDay() != 0) {
        day = d.getDay();
        diff = d.getDate() - day + 7 + addMinus; // adjust when day is sunday
      } else {
        diff = d.getDate();
      }

      // console.log("getDate: " + d.getDate());
      // console.log("getDay: " + d.getDay());
      // console.log("Sunday: " + diff);

      var newDate = new Date(d.setDate(diff));

      var today = new Date(newDate);
      var dd = today.getDate();
      var mm = today.getMonth() + 1;
      var yyyy = today.getFullYear();

      if (dd < 10) {
        dd = "0" + dd;
      }

      if (mm < 10) {
        mm = "0" + mm;
      }
      today = yyyy + "-" + mm + "-" + dd;
      return today;
    },
    previousDate(d) {
      let app = this;
      app.report.start_date = app.getMonday(d, -7);
      app.report.end_date = app.getSunday(d, -7);
    },
    nextDate(d) {
      let app = this;
      app.report.start_date = app.getMonday(d, 7);
      app.report.end_date = app.getSunday(app.report.start_date, 0);
    },
    loadproducts() {
      var app = this;
      app.isLoading = true;
      axios
        .get("/auth/product/list/" + this.$auth.user().store_name)
        .then(function(resp) {
          app.products = resp.data.records;
          var local_tags = app.products
            .map(item => item.vendor)
            .filter((value, index, self) => self.indexOf(value) === index);

          $.each(local_tags, function(key1, value1) {
            if (value1) {
              app.summary.vendors.push({
                title: value1,
                value: true
              });
              app.stocks.vendors.push({
                title: value1,
                value: true
              });

              app.stocks.draftvendors.push({
                title: value1,
                value: true
              });
            }
          });
          app.isLoading = false;
          app.$store.commit(
            "changeProducts",
            JSON.parse(JSON.stringify(app.products))
          );
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not load products");
          app.isLoading = false;
        });
    },
    loadOrders() {
      var app = this;
      app.isLoading = true;
      axios
        .get("/auth/order/list/" + this.$auth.user().store_name)
        .then(function(resp) {
          app.orders = resp.data.records;
          app.isLoading = false;
          app.$store.commit(
            "changeOrders",
            JSON.parse(JSON.stringify(app.orders))
          );
          app.getAllTags();
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not load orders");
          app.isLoading = false;
        });
    },
    countOrders(order) {
      var quantity = 0;
      $.each(order, function(key, value) {
        quantity += value.quantity;
      });
      return quantity;
    },
    getAllTags() {
      let app = this;
      var local_tags = [];
      $.each(app.orders, function(key1, value1) {
        if (value1.tags) {
          $.each(value1.tags.split(","), function(key2, value2) {
            if (!local_tags.includes(value2)) {
              local_tags.push(value2);
              app.summary.tags.push({ title: value2, value: true });
            }
          });
        }
      });
    }
  }
};
</script>
<style lang="scss" scoped>
.truncate {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.options {
  float: right;
  color: blue;
  font-style: lowercase;
  font-size: 11px;
  cursor: pointer;
}
.show_options {
  display: block;
}

.hide_options {
  display: none;
}
/* Tooltip container */
.custom-tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
}

/* Tooltip text */
.custom-tooltip .custom-tooltiptext {
  visibility: hidden;
  width: 330px;
  background-color: black;
  color: #fff;
  // text-align: center;
  padding: 5px 0;
  border-radius: 6px;
  top: -5px;
  right: 105%;
  /* Position the tooltip text - see examples below! */
  position: absolute;
  z-index: 1;
}

/* Show the tooltip text when you mouse over the tooltip container */
.custom-tooltip:hover .custom-tooltiptext {
  visibility: visible;
}

.table-responsive {
  max-height: 40vh;
  overflow: hidden;
  // overflow-y: scroll;
  // overflow-x: scroll;
}

.table-responsive-small {
  max-height: 40vh;
  overflow: hidden;
}
.table-responsive-small:hover {
  overflow-y: auto;
}

.table-responsive2 {
  width: 100%;
  max-height: 40vh;
  overflow-y: hidden;
  overflow-x: hidden;
  text-align: center;
}

.table-responsive2:hover {
  overflow-y: auto;
}

.table-responsive:hover {
  overflow: auto;
}

/* Hide Horizontal Scrollbar on Auto scroll carousel */
// .table-responsive::-webkit-scrollbar {
//   width: 5px; /* Remove scrollbar space */
//   background: #c1c1c1; /* Optional: just make scrollbar invisible */
// }

.table {
  margin: 0px;
  width: 100%;
  text-align: center;
  // min-width: 1200px;
}

td {
  padding: 3px !important;
}

.Today {
  font-size: 12px;
  background-color: #35cc40;
  border: 2px solid #35cc40;
  padding: 0px 3px 0px 3px;
  border-radius: 20%;
  color: white;
  margin-right: 5px;
}

.Tomorrow {
  font-size: 12px;
  background-color: #ff6600;
  border: 2px solid #ff6600;
  padding: 0px 3px 0px 3px;
  border-radius: 20%;
  color: white;
  margin-right: 5px;
}

.Delayed {
  font-size: 12px;
  background-color: #ff0000;
  border: 2px solid #ff0000;
  padding: 0px 3px 0px 3px;
  border-radius: 20%;
  color: white;
  margin-right: 5px;
}

.Advance {
  font-size: 12px;
  background-color: #0010a5;
  border: 2px solid #0010a5;
  padding: 0px 3px 0px 3px;
  border-radius: 20%;
  color: white;
  margin-right: 5px;
}

.Yesterday {
  font-size: 12px;
  background-color: #c2c500;
  border: 2px solid #c2c500;
  padding: 0px 3px 0px 3px;
  border-radius: 20%;
  color: white;
  margin-right: 5px;
}
</style>
