$(document).ready(function() {
    // 自动验证input value
    require('../plugins/automaticValidationForm');
    // 登录模态框切换tab
    $('.login-panel .title').on('click',function clickLoginPanelTitle(event) {
        const panelId = $(event.target).attr('data-panel');
        $(event.target).addClass('active').siblings().removeClass('active');
        $('.panel').removeClass('active');
        $(panelId).addClass('active')
    });
    // 登录事件监听
    $('[form-validate]').on('login',function name(event) {
        const $form = $($(this).attr('form-validate'));
        const params = $form.serialize();
        const temp_text = $(this).text();
        $(this).text('登录中...').addClass('disabled')
        $.ajax({
            type: 'POST',
            url: `/login?${params}`,
            cache: true,
            processData: false,
            contentType: false,
        }).done(function(res) {
            if(res.data) {
                // 登录成功
                window.location.reload()
                $('#login-modal').modal('hide')
            }else if(res.message) {
                // 登录失败
                $form.find('[validate-rule]').each(function onValidateInput(index) {
                    const required = $(this).attr('data-required');
                    const value = $(this).children('input').val();
                    if(required || value.length > 0) {
                        $(this).find('.help-block').children().text(res.message)
                        $(this).removeClass('with-success').addClass('with-error')
                    }
                })
            }
        }).fail(function(err) {
            // 登录报错
        }).always(()=>{
            $(this).text(temp_text).removeClass('disabled')
        });
    });
    // 退出登录
    $('.logout').on('click',function name(event) {
        $.ajax({
            type: 'POST',
            url: `/logout`,
            processData: false,
            contentType: false,
        }).done(function(res) {
            if(res.data) {
                window.location.reload()
            }else if(res.message) {

            }
        }).fail(function(err) {

        });
    });
    // dropdown box 事件监听
    (function Dropdown(that) {
        function clearDropdowns() {
            $('.dropdown-box').removeClass('open')
        }
        $(document).on('click', clearDropdowns);
        function toggle(e) {
            var $this = $(this);
            if ($this.is('.disabled, :disabled') || $(event.target).parents('.dropdown-box')[0]) return;
            // 包含下拉框的容器
            var selector = $($(this).attr('dropdown-target'))[0];
            var $dropdown = selector && $(selector);
            var isActive = $dropdown.hasClass('open')
            // 先隐藏页面的所有下拉框
            clearDropdowns()
            // 如果点击元素的容器没有.open（即下拉框隐藏）
            if (!isActive) {
                if (e.isDefaultPrevented()) return
                // $this.trigger('focus')
                $dropdown.toggleClass('open')
            }
            // 阻止冒泡
            return false
        }
        $('[dropdown-target]').on('click touchstart', toggle);
        $('[dropdown-toggle="hover"]').hover(
            toggle,
            function hide(event) {
                $(this).find('.dropdown-box').removeClass('open')
            }
        )
    }());
})
